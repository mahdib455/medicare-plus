<?php

namespace App\Http\Controllers;

use App\Models\SymptomCheck;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AIController extends Controller
{
    /**
     * Analyze symptoms using AI-like pattern matching.
     */
    public function analyzeSymptoms(Request $request)
    {
        // Validate input - AI should be flexible, accept even short symptoms
        $request->validate([
            'symptom_text' => 'required|string|min:1|max:1000',
        ]);

        $symptomText = strtolower($request->symptom_text);
        
        // Get authenticated user (for now, use first user if no auth)
        $user = Auth::user() ?? User::first();
        
        if (!$user) {
            return response()->json([
                'error' => 'User not found'
            ], 404);
        }

        try {
            // Analyze symptoms
            $analysis = $this->performSymptomAnalysis($symptomText);

            if (!$analysis || !is_array($analysis)) {
                throw new \Exception('Failed to analyze symptoms - invalid analysis result');
            }

            // Save to database
            $symptomCheck = SymptomCheck::create([
                'user_id' => $user->id,
                'symptom_text' => $request->symptom_text,
                'result' => $analysis['diagnosis'] ?? 'Analysis completed',
                'recommended_doctor' => $analysis['recommended_doctor'] ?? 'General Practitioner',
                'urgency_level' => $analysis['urgency_level'] ?? 3,
                'severity' => $analysis['severity'] ?? 3,
                'detected_categories' => $analysis['categories'] ?? ['General'],
                'analysis' => [
                    'confidence' => $analysis['confidence'] ?? 50,
                    'primary_condition' => $analysis['primary_condition'] ?? 'General Symptoms',
                    'secondary_conditions' => $analysis['secondary_conditions'] ?? [],
                    'recommendations' => $analysis['recommendations'] ?? ['Consult healthcare provider'],
                    'warning_signs' => $analysis['warning_signs'] ?? [],
                ]
            ]);

            // Load the saved record with user relationship
            $symptomCheck->load('user');

            return response()->json([
                'success' => true,
                'message' => 'Symptoms analyzed successfully',
                'data' => $symptomCheck,
                'analysis' => [
                    'diagnosis' => $analysis['diagnosis'],
                    'recommended_doctor' => $analysis['recommended_doctor'],
                    'urgency_level' => $analysis['urgency_level'],
                    'urgency_text' => $symptomCheck->urgency_level_text,
                    'severity' => $analysis['severity'],
                    'severity_text' => $symptomCheck->severity_text,
                    'categories' => $analysis['categories'],
                    'confidence' => $analysis['confidence'],
                    'recommendations' => $analysis['recommendations'],
                    'warning_signs' => $analysis['warning_signs'],
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error analyzing symptoms: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to analyze symptoms',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Perform symptom analysis using pattern matching.
     */
    private function performSymptomAnalysis($symptomText)
    {
        $conditions = $this->getConditionPatterns();
        $matches = [];
        $totalScore = 0;

        // Normalize text for better matching
        $normalizedText = $this->normalizeText($symptomText);

        // Check each condition for keyword matches
        foreach ($conditions as $condition => $data) {
            $score = 0;
            $matchedKeywords = [];

            foreach ($data['keywords'] as $keyword) {
                $normalizedKeyword = $this->normalizeText($keyword);

                // Exact match
                if (strpos($normalizedText, $normalizedKeyword) !== false) {
                    $score += $data['weight'];
                    $matchedKeywords[] = $keyword;
                }

                // Partial match for compound words
                $keywordParts = explode(' ', $normalizedKeyword);
                if (count($keywordParts) > 1) {
                    $partialMatches = 0;
                    foreach ($keywordParts as $part) {
                        if (strlen($part) > 2 && strpos($normalizedText, $part) !== false) {
                            $partialMatches++;
                        }
                    }
                    if ($partialMatches >= count($keywordParts) * 0.7) {
                        $score += $data['weight'] * 0.7;
                        $matchedKeywords[] = $keyword . ' (partial)';
                    }
                }
            }

            if ($score > 0) {
                $matches[$condition] = [
                    'score' => $score,
                    'keywords' => $matchedKeywords,
                    'data' => $data
                ];
                $totalScore += $score;
            }
        }

        // Determine primary condition
        if (empty($matches)) {
            return $this->getDefaultAnalysis($symptomText);
        }

        // Sort by score
        uasort($matches, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        $primaryCondition = array_key_first($matches);
        $primaryData = $matches[$primaryCondition]['data'];
        
        // Calculate confidence
        $confidence = min(95, ($matches[$primaryCondition]['score'] / max(1, $totalScore)) * 100);
        
        // Get secondary conditions
        $secondaryConditions = array_slice(array_keys($matches), 1, 2);

        return [
            'primary_condition' => $primaryCondition,
            'diagnosis' => $primaryData['diagnosis'],
            'recommended_doctor' => $primaryData['doctor'],
            'urgency_level' => $this->calculateUrgencyLevel($matches, $primaryData, $symptomText),
            'severity' => $this->calculateSeverity($matches, $primaryData, $symptomText),
            'categories' => $this->extractCategories($matches),
            'confidence' => round($confidence),
            'secondary_conditions' => $secondaryConditions,
            'recommendations' => $primaryData['recommendations'],
            'warning_signs' => $primaryData['warning_signs'] ?? []
        ];
    }

    /**
     * Get condition patterns for matching.
     */
    private function getConditionPatterns()
    {
        return [
            'flu' => [
                'keywords' => ['fever', 'chills', 'body ache', 'fatigue', 'runny nose', 'sore throat', 'cough', 'muscle pain', 'aches', 'tired', 'exhausted', 'weak', 'influenza', 'flu', 'headache', 'head ache'],
                'weight' => 10,
                'diagnosis' => 'Based on your symptoms, you may have influenza (flu). Common symptoms include fever, body aches, and respiratory symptoms.',
                'doctor' => 'General Practitioner',
                'base_urgency' => 4,
                'base_severity' => 4,
                'categories' => ['Respiratory', 'Viral Infection'],
                'recommendations' => ['Rest and hydration', 'Over-the-counter pain relievers', 'Avoid contact with others'],
                'warning_signs' => ['High fever over 103°F', 'Difficulty breathing', 'Chest pain']
            ],
            'common_cold' => [
                'keywords' => ['runny nose', 'stuffy nose', 'sneezing', 'mild cough', 'scratchy throat', 'watery eyes', 'congestion', 'blocked nose'],
                'weight' => 8,
                'diagnosis' => 'Your symptoms suggest a common cold. This is usually a mild viral infection that resolves on its own.',
                'doctor' => 'General Practitioner',
                'base_urgency' => 2,
                'base_severity' => 2,
                'categories' => ['Respiratory', 'Viral Infection'],
                'recommendations' => ['Rest', 'Drink fluids', 'Use saline nasal spray', 'Throat lozenges'],
                'warning_signs' => ['High fever', 'Severe headache', 'Difficulty swallowing']
            ],
            'migraine' => [
                'keywords' => ['headache', 'nausea', 'light sensitivity', 'sound sensitivity', 'throbbing', 'pulsing', 'vomiting', 'head ache', 'head pain'],
                'weight' => 12,
                'diagnosis' => 'Your symptoms suggest a possible migraine. Migraines often involve severe headaches with nausea and sensitivity to light or sound.',
                'doctor' => 'Neurologist',
                'base_urgency' => 5,
                'base_severity' => 6,
                'categories' => ['Neurological', 'Headache'],
                'recommendations' => ['Rest in dark, quiet room', 'Apply cold compress', 'Stay hydrated'],
                'warning_signs' => ['Sudden severe headache', 'Fever with headache', 'Vision changes']
            ],
            'headache_fever' => [
                'keywords' => ['headache', 'fever', 'head ache', 'head pain', 'temperature', 'hot'],
                'weight' => 11,
                'diagnosis' => 'Your symptoms of headache and fever may indicate a viral infection, flu, or other condition requiring medical attention.',
                'doctor' => 'General Practitioner',
                'base_urgency' => 5,
                'base_severity' => 5,
                'categories' => ['Neurological', 'Infection', 'Fever'],
                'recommendations' => ['Rest and hydration', 'Monitor temperature', 'Pain relievers', 'Seek medical attention if symptoms worsen'],
                'warning_signs' => ['High fever over 102°F', 'Severe headache', 'Neck stiffness', 'Confusion']
            ],
            'covid' => [
                'keywords' => ['dry cough', 'loss of taste', 'loss of smell', 'shortness of breath', 'covid', 'coronavirus'],
                'weight' => 15,
                'diagnosis' => 'Your symptoms may indicate COVID-19. Please get tested and isolate yourself from others.',
                'doctor' => 'General Practitioner',
                'base_urgency' => 7,
                'base_severity' => 6,
                'categories' => ['Respiratory', 'Viral Infection', 'Infectious Disease'],
                'recommendations' => ['Get tested immediately', 'Self-isolate', 'Monitor symptoms closely'],
                'warning_signs' => ['Difficulty breathing', 'Chest pain', 'High fever', 'Confusion']
            ],
            'heart_issues' => [
                'keywords' => ['chest pain', 'heart palpitations', 'shortness of breath', 'dizziness', 'sweating', 'arm pain'],
                'weight' => 20,
                'diagnosis' => 'Your symptoms may indicate a cardiac condition. This requires immediate medical attention.',
                'doctor' => 'Cardiologist',
                'base_urgency' => 9,
                'base_severity' => 8,
                'categories' => ['Cardiovascular', 'Emergency'],
                'recommendations' => ['Seek immediate medical attention', 'Call emergency services', 'Do not drive yourself'],
                'warning_signs' => ['Severe chest pain', 'Radiating arm pain', 'Difficulty breathing']
            ],
            'allergy' => [
                'keywords' => ['sneezing', 'itchy eyes', 'runny nose', 'rash', 'hives', 'swelling', 'allergic reaction'],
                'weight' => 8,
                'diagnosis' => 'Your symptoms suggest an allergic reaction. This could be seasonal allergies or reaction to a specific allergen.',
                'doctor' => 'Allergist',
                'base_urgency' => 3,
                'base_severity' => 3,
                'categories' => ['Allergy', 'Immunological'],
                'recommendations' => ['Avoid known allergens', 'Antihistamines', 'Cool compress for rash'],
                'warning_signs' => ['Difficulty breathing', 'Severe swelling', 'Anaphylaxis symptoms']
            ],
            'diabetes' => [
                'keywords' => ['excessive thirst', 'frequent urination', 'blurred vision', 'fatigue', 'weight loss', 'high blood sugar'],
                'weight' => 12,
                'diagnosis' => 'Your symptoms may indicate diabetes or blood sugar issues. Please consult a healthcare provider for proper testing.',
                'doctor' => 'Endocrinologist',
                'base_urgency' => 6,
                'base_severity' => 5,
                'categories' => ['Endocrine', 'Metabolic'],
                'recommendations' => ['Monitor blood sugar', 'Healthy diet', 'Regular exercise', 'Medical evaluation'],
                'warning_signs' => ['Very high blood sugar', 'Ketones in urine', 'Severe dehydration']
            ],
            'depression' => [
                'keywords' => ['sad', 'depressed', 'hopeless', 'loss of interest', 'sleep problems', 'appetite changes', 'fatigue'],
                'weight' => 10,
                'diagnosis' => 'Your symptoms may indicate depression. Mental health is important and professional help is available.',
                'doctor' => 'Psychiatrist',
                'base_urgency' => 5,
                'base_severity' => 6,
                'categories' => ['Mental Health', 'Psychological'],
                'recommendations' => ['Seek professional counseling', 'Stay connected with support system', 'Regular exercise'],
                'warning_signs' => ['Suicidal thoughts', 'Self-harm', 'Complete isolation']
            ],
            'asthma' => [
                'keywords' => ['wheezing', 'shortness of breath', 'chest tightness', 'coughing', 'breathing difficulty'],
                'weight' => 14,
                'diagnosis' => 'Your symptoms suggest asthma or respiratory issues. Proper diagnosis and treatment are important.',
                'doctor' => 'Pulmonologist',
                'base_urgency' => 6,
                'base_severity' => 5,
                'categories' => ['Respiratory', 'Chronic Condition'],
                'recommendations' => ['Use prescribed inhaler', 'Avoid triggers', 'Monitor peak flow'],
                'warning_signs' => ['Severe breathing difficulty', 'Blue lips or fingernails', 'Cannot speak in full sentences']
            ],
            'uti' => [
                'keywords' => ['burning urination', 'frequent urination', 'cloudy urine', 'pelvic pain', 'urinary tract'],
                'weight' => 11,
                'diagnosis' => 'Your symptoms suggest a urinary tract infection (UTI). This typically requires antibiotic treatment.',
                'doctor' => 'Urologist',
                'base_urgency' => 4,
                'base_severity' => 4,
                'categories' => ['Urological', 'Infection'],
                'recommendations' => ['Drink plenty of water', 'Cranberry juice', 'Seek medical treatment for antibiotics'],
                'warning_signs' => ['Fever', 'Back pain', 'Blood in urine']
            ],
            'arthritis' => [
                'keywords' => ['joint pain', 'stiffness', 'swelling', 'morning stiffness', 'joint inflammation', 'aching joints', 'painful joints'],
                'weight' => 9,
                'diagnosis' => 'Your symptoms may indicate arthritis or joint inflammation. This can be managed with proper treatment.',
                'doctor' => 'Rheumatologist',
                'base_urgency' => 3,
                'base_severity' => 4,
                'categories' => ['Rheumatological', 'Musculoskeletal'],
                'recommendations' => ['Gentle exercise', 'Anti-inflammatory medication', 'Heat/cold therapy'],
                'warning_signs' => ['Severe joint deformity', 'Complete loss of function', 'Signs of infection']
            ],
            'headache_general' => [
                'keywords' => ['headache', 'head ache', 'head pain', 'head hurt', 'head hurts', 'skull pain', 'my head', 'head is', 'cranium'],
                'weight' => 8,
                'diagnosis' => 'You are experiencing headache symptoms. Headaches can have various causes including tension, dehydration, stress, or underlying conditions.',
                'doctor' => 'General Practitioner',
                'base_urgency' => 3,
                'base_severity' => 4,
                'categories' => ['Neurological', 'Pain'],
                'recommendations' => ['Rest in quiet environment', 'Stay hydrated', 'Apply cold or warm compress', 'Over-the-counter pain relievers'],
                'warning_signs' => ['Sudden severe headache', 'Headache with fever', 'Vision changes', 'Neck stiffness']
            ],
            'fever_general' => [
                'keywords' => ['fever', 'temperature', 'hot', 'burning up', 'feverish', 'high temperature'],
                'weight' => 9,
                'diagnosis' => 'You have fever symptoms which typically indicate your body is fighting an infection or illness.',
                'doctor' => 'General Practitioner',
                'base_urgency' => 4,
                'base_severity' => 4,
                'categories' => ['Infection', 'Fever'],
                'recommendations' => ['Rest and hydration', 'Monitor temperature', 'Fever reducers if needed', 'Seek medical attention if high fever persists'],
                'warning_signs' => ['Fever over 103°F', 'Difficulty breathing', 'Severe dehydration', 'Confusion']
            ],
            'gastroenteritis' => [
                'keywords' => ['stomach pain', 'diarrhea', 'vomiting', 'nausea', 'stomach cramps', 'abdominal pain', 'upset stomach', 'food poisoning', 'pain', 'vomit', 'diarrhea', 'loose stool', 'watery stool', 'stomach ache', 'belly pain', 'gut pain', 'intestinal pain'],
                'weight' => 11,
                'diagnosis' => 'Your symptoms suggest gastroenteritis (stomach flu) or food poisoning. This usually resolves within a few days.',
                'doctor' => 'General Practitioner',
                'base_urgency' => 4,
                'base_severity' => 4,
                'categories' => ['Gastrointestinal', 'Infection'],
                'recommendations' => ['Stay hydrated', 'BRAT diet', 'Rest', 'Electrolyte replacement'],
                'warning_signs' => ['Severe dehydration', 'Blood in stool', 'High fever', 'Severe abdominal pain']
            ],
            'anxiety' => [
                'keywords' => ['anxious', 'worried', 'panic', 'nervous', 'restless', 'racing heart', 'sweating', 'trembling', 'fear'],
                'weight' => 10,
                'diagnosis' => 'Your symptoms may indicate anxiety or panic disorder. Mental health support is available.',
                'doctor' => 'Psychiatrist',
                'base_urgency' => 4,
                'base_severity' => 5,
                'categories' => ['Mental Health', 'Psychological'],
                'recommendations' => ['Deep breathing exercises', 'Relaxation techniques', 'Professional counseling', 'Regular exercise'],
                'warning_signs' => ['Panic attacks', 'Suicidal thoughts', 'Complete inability to function']
            ],
            'hypertension' => [
                'keywords' => ['high blood pressure', 'headache', 'dizziness', 'blurred vision', 'chest pain', 'shortness of breath'],
                'weight' => 12,
                'diagnosis' => 'Your symptoms may indicate high blood pressure (hypertension). This requires medical monitoring.',
                'doctor' => 'Cardiologist',
                'base_urgency' => 6,
                'base_severity' => 5,
                'categories' => ['Cardiovascular', 'Chronic Condition'],
                'recommendations' => ['Monitor blood pressure', 'Reduce sodium intake', 'Regular exercise', 'Stress management'],
                'warning_signs' => ['Severe headache', 'Chest pain', 'Difficulty breathing', 'Vision problems']
            ],
            'pneumonia' => [
                'keywords' => ['chest pain', 'cough', 'fever', 'shortness of breath', 'chills', 'fatigue', 'phlegm', 'breathing difficulty'],
                'weight' => 15,
                'diagnosis' => 'Your symptoms may indicate pneumonia, a serious lung infection that requires medical treatment.',
                'doctor' => 'Pulmonologist',
                'base_urgency' => 7,
                'base_severity' => 7,
                'categories' => ['Respiratory', 'Infection'],
                'recommendations' => ['Seek immediate medical attention', 'Rest', 'Stay hydrated', 'Take prescribed antibiotics'],
                'warning_signs' => ['Severe breathing difficulty', 'Blue lips', 'High fever', 'Confusion']
            ],
            'kidney_stones' => [
                'keywords' => ['severe back pain', 'side pain', 'painful urination', 'blood in urine', 'nausea', 'vomiting', 'kidney pain'],
                'weight' => 13,
                'diagnosis' => 'Your symptoms may indicate kidney stones. This can cause severe pain and requires medical evaluation.',
                'doctor' => 'Urologist',
                'base_urgency' => 7,
                'base_severity' => 8,
                'categories' => ['Urological', 'Pain'],
                'recommendations' => ['Drink plenty of water', 'Pain management', 'Seek medical attention', 'Strain urine'],
                'warning_signs' => ['Severe pain', 'Fever', 'Unable to urinate', 'Persistent vomiting']
            ],
            'appendicitis' => [
                'keywords' => ['right lower abdominal pain', 'appendix pain', 'nausea', 'vomiting', 'fever', 'loss of appetite'],
                'weight' => 18,
                'diagnosis' => 'Your symptoms may indicate appendicitis, which is a medical emergency requiring immediate surgery.',
                'doctor' => 'Emergency Medicine',
                'base_urgency' => 9,
                'base_severity' => 9,
                'categories' => ['Gastrointestinal', 'Emergency', 'Surgical'],
                'recommendations' => ['Seek emergency medical attention immediately', 'Do not eat or drink', 'Go to emergency room'],
                'warning_signs' => ['Severe abdominal pain', 'High fever', 'Vomiting', 'Inability to walk']
            ],
            'stroke' => [
                'keywords' => ['sudden weakness', 'face drooping', 'arm weakness', 'speech difficulty', 'confusion', 'severe headache', 'vision loss'],
                'weight' => 20,
                'diagnosis' => 'Your symptoms may indicate a stroke, which is a medical emergency. Call emergency services immediately.',
                'doctor' => 'Emergency Medicine',
                'base_urgency' => 10,
                'base_severity' => 10,
                'categories' => ['Neurological', 'Emergency', 'Cardiovascular'],
                'recommendations' => ['Call emergency services immediately', 'Do not drive', 'Note time of symptom onset'],
                'warning_signs' => ['All symptoms are warning signs - seek immediate help']
            ],
            'sinusitis' => [
                'keywords' => ['sinus pressure', 'facial pain', 'nasal congestion', 'thick nasal discharge', 'headache', 'tooth pain', 'sinus infection'],
                'weight' => 9,
                'diagnosis' => 'Your symptoms suggest sinusitis (sinus infection). This can be bacterial or viral.',
                'doctor' => 'ENT Specialist',
                'base_urgency' => 3,
                'base_severity' => 4,
                'categories' => ['Respiratory', 'Infection'],
                'recommendations' => ['Nasal irrigation', 'Steam inhalation', 'Decongestants', 'Warm compress'],
                'warning_signs' => ['High fever', 'Severe headache', 'Vision changes', 'Neck stiffness']
            ],
            'bronchitis' => [
                'keywords' => ['persistent cough', 'mucus', 'chest congestion', 'wheezing', 'fatigue', 'mild fever', 'sore throat'],
                'weight' => 10,
                'diagnosis' => 'Your symptoms suggest bronchitis, an inflammation of the bronchial tubes.',
                'doctor' => 'Pulmonologist',
                'base_urgency' => 4,
                'base_severity' => 4,
                'categories' => ['Respiratory', 'Infection'],
                'recommendations' => ['Rest', 'Drink fluids', 'Humidifier', 'Cough suppressants'],
                'warning_signs' => ['High fever', 'Blood in mucus', 'Severe breathing difficulty']
            ],
            'food_allergy' => [
                'keywords' => ['hives', 'swelling', 'itching', 'difficulty swallowing', 'stomach cramps', 'diarrhea', 'allergic reaction'],
                'weight' => 12,
                'diagnosis' => 'Your symptoms suggest a food allergic reaction. Severity can vary from mild to life-threatening.',
                'doctor' => 'Allergist',
                'base_urgency' => 6,
                'base_severity' => 5,
                'categories' => ['Allergy', 'Immunological'],
                'recommendations' => ['Avoid trigger foods', 'Antihistamines', 'Carry epinephrine if prescribed'],
                'warning_signs' => ['Difficulty breathing', 'Severe swelling', 'Rapid pulse', 'Dizziness']
            ],
            'insomnia' => [
                'keywords' => ['cannot sleep', 'trouble sleeping', 'sleepless', 'insomnia', 'tired', 'exhausted', 'sleep problems'],
                'weight' => 7,
                'diagnosis' => 'Your symptoms suggest insomnia or sleep disorders. Good sleep hygiene is important for health.',
                'doctor' => 'Sleep Specialist',
                'base_urgency' => 2,
                'base_severity' => 3,
                'categories' => ['Sleep Disorder', 'Mental Health'],
                'recommendations' => ['Sleep hygiene', 'Regular sleep schedule', 'Avoid caffeine', 'Relaxation techniques'],
                'warning_signs' => ['Severe daytime impairment', 'Depression', 'Hallucinations']
            ],
            'skin_infection' => [
                'keywords' => ['rash', 'red skin', 'itchy skin', 'skin irritation', 'bumps', 'blisters', 'skin lesions'],
                'weight' => 8,
                'diagnosis' => 'Your symptoms suggest a skin condition or infection. Proper diagnosis requires examination.',
                'doctor' => 'Dermatologist',
                'base_urgency' => 3,
                'base_severity' => 3,
                'categories' => ['Dermatological', 'Infection'],
                'recommendations' => ['Keep area clean', 'Avoid scratching', 'Topical treatments', 'See dermatologist'],
                'warning_signs' => ['Spreading rapidly', 'Fever', 'Pus', 'Red streaks']
            ],
            'back_pain' => [
                'keywords' => ['back pain', 'lower back pain', 'spine pain', 'muscle spasm', 'stiff back', 'sciatica'],
                'weight' => 8,
                'diagnosis' => 'Your symptoms suggest back pain, which can have various causes from muscle strain to disc problems.',
                'doctor' => 'Orthopedist',
                'base_urgency' => 3,
                'base_severity' => 4,
                'categories' => ['Musculoskeletal', 'Pain'],
                'recommendations' => ['Rest', 'Ice/heat therapy', 'Gentle stretching', 'Pain relievers'],
                'warning_signs' => ['Numbness in legs', 'Loss of bladder control', 'Severe pain', 'Weakness']
            ],
            'ear_infection' => [
                'keywords' => ['ear pain', 'earache', 'hearing loss', 'ear discharge', 'ringing in ears', 'dizziness'],
                'weight' => 9,
                'diagnosis' => 'Your symptoms suggest an ear infection, which can affect the outer, middle, or inner ear.',
                'doctor' => 'ENT Specialist',
                'base_urgency' => 4,
                'base_severity' => 4,
                'categories' => ['ENT', 'Infection'],
                'recommendations' => ['Pain relievers', 'Warm compress', 'Avoid water in ear', 'See doctor for antibiotics'],
                'warning_signs' => ['High fever', 'Severe pain', 'Hearing loss', 'Facial weakness']
            ],
            'thyroid_issues' => [
                'keywords' => ['weight gain', 'weight loss', 'fatigue', 'hair loss', 'cold intolerance', 'heat intolerance', 'thyroid'],
                'weight' => 10,
                'diagnosis' => 'Your symptoms may indicate thyroid dysfunction (hyperthyroidism or hypothyroidism).',
                'doctor' => 'Endocrinologist',
                'base_urgency' => 4,
                'base_severity' => 4,
                'categories' => ['Endocrine', 'Hormonal'],
                'recommendations' => ['Blood tests', 'Regular monitoring', 'Medication compliance', 'Follow-up care'],
                'warning_signs' => ['Rapid heart rate', 'Severe fatigue', 'Difficulty breathing', 'Chest pain']
            ],
            'digestive_issues' => [
                'keywords' => ['pain', 'stomach', 'belly', 'gut', 'intestinal', 'bowel', 'digestive', 'gastrointestinal'],
                'weight' => 6,
                'diagnosis' => 'Your symptoms suggest digestive or gastrointestinal issues. This could be related to diet, infection, or other digestive conditions.',
                'doctor' => 'Gastroenterologist',
                'base_urgency' => 3,
                'base_severity' => 3,
                'categories' => ['Gastrointestinal', 'Digestive'],
                'recommendations' => ['Monitor your diet', 'Stay hydrated', 'Avoid spicy foods', 'Consider probiotics'],
                'warning_signs' => ['Severe pain', 'Blood in stool', 'High fever', 'Persistent vomiting']
            ],
            'general_pain' => [
                'keywords' => ['pain', 'hurt', 'ache', 'sore', 'discomfort', 'tender'],
                'weight' => 4,
                'diagnosis' => 'You are experiencing pain symptoms. The location and nature of pain can help determine the underlying cause.',
                'doctor' => 'General Practitioner',
                'base_urgency' => 3,
                'base_severity' => 3,
                'categories' => ['Pain', 'General'],
                'recommendations' => ['Rest the affected area', 'Apply ice or heat as appropriate', 'Over-the-counter pain relievers', 'Monitor symptoms'],
                'warning_signs' => ['Severe or worsening pain', 'Signs of infection', 'Loss of function', 'Numbness or tingling']
            ],
            'cough' => [
                'keywords' => ['cough', 'coughing', 'hack', 'throat clearing'],
                'weight' => 7,
                'diagnosis' => 'You have a cough which can be caused by various conditions including cold, flu, allergies, or respiratory infections.',
                'doctor' => 'General Practitioner',
                'base_urgency' => 2,
                'base_severity' => 2,
                'categories' => ['Respiratory'],
                'recommendations' => ['Stay hydrated', 'Throat lozenges', 'Honey for soothing', 'Rest'],
                'warning_signs' => ['Blood in cough', 'Difficulty breathing', 'High fever', 'Chest pain']
            ],
            'tired' => [
                'keywords' => ['tired', 'fatigue', 'exhausted', 'weak', 'sleepy', 'drowsy'],
                'weight' => 5,
                'diagnosis' => 'You are experiencing fatigue which can be caused by lack of sleep, stress, illness, or underlying conditions.',
                'doctor' => 'General Practitioner',
                'base_urgency' => 2,
                'base_severity' => 2,
                'categories' => ['General', 'Fatigue'],
                'recommendations' => ['Get adequate sleep', 'Reduce stress', 'Healthy diet', 'Regular exercise'],
                'warning_signs' => ['Extreme fatigue', 'Unexplained weight loss', 'Persistent symptoms']
            ],
            'dizzy' => [
                'keywords' => ['dizzy', 'dizziness', 'lightheaded', 'vertigo', 'spinning', 'unsteady'],
                'weight' => 8,
                'diagnosis' => 'You are experiencing dizziness which can be caused by inner ear problems, low blood pressure, dehydration, or other conditions.',
                'doctor' => 'General Practitioner',
                'base_urgency' => 4,
                'base_severity' => 3,
                'categories' => ['Neurological', 'Balance'],
                'recommendations' => ['Sit or lie down', 'Stay hydrated', 'Avoid sudden movements', 'Rest'],
                'warning_signs' => ['Severe dizziness', 'Fainting', 'Chest pain', 'Difficulty speaking']
            ],
            'nausea' => [
                'keywords' => ['nausea', 'nauseous', 'sick', 'queasy', 'upset stomach'],
                'weight' => 7,
                'diagnosis' => 'You are experiencing nausea which can be caused by various factors including food poisoning, motion sickness, pregnancy, or illness.',
                'doctor' => 'General Practitioner',
                'base_urgency' => 3,
                'base_severity' => 3,
                'categories' => ['Gastrointestinal'],
                'recommendations' => ['Sip clear fluids', 'Eat bland foods', 'Rest', 'Ginger tea'],
                'warning_signs' => ['Persistent vomiting', 'Dehydration', 'Severe abdominal pain', 'Blood in vomit']
            ],
            'cold' => [
                'keywords' => ['cold', 'sniffles', 'stuffy', 'congested'],
                'weight' => 6,
                'diagnosis' => 'You appear to have cold symptoms. The common cold is a viral infection that usually resolves on its own.',
                'doctor' => 'General Practitioner',
                'base_urgency' => 2,
                'base_severity' => 2,
                'categories' => ['Respiratory', 'Viral Infection'],
                'recommendations' => ['Rest', 'Drink fluids', 'Throat lozenges', 'Saline nasal spray'],
                'warning_signs' => ['High fever', 'Severe headache', 'Difficulty breathing']
            ]
        ];
    }

    /**
     * Calculate urgency level based on matches and primary condition.
     */
    private function calculateUrgencyLevel($matches, $primaryData, $symptomText = '')
    {
        $baseUrgency = $primaryData['base_urgency'];

        // Increase urgency for multiple conditions
        if (count($matches) > 2) {
            $baseUrgency += 1;
        }

        // Check for emergency keywords
        $emergencyKeywords = ['chest pain', 'difficulty breathing', 'severe', 'emergency', 'urgent', 'intense', 'excruciating'];
        foreach ($emergencyKeywords as $keyword) {
            if (strpos(strtolower($symptomText), strtolower($keyword)) !== false) {
                $baseUrgency += 2;
                break;
            }
        }

        return min(10, max(1, $baseUrgency));
    }

    /**
     * Calculate severity based on matches and primary condition.
     */
    private function calculateSeverity($matches, $primaryData, $symptomText = '')
    {
        $baseSeverity = $primaryData['base_severity'];

        // Increase severity for multiple conditions
        if (count($matches) > 2) {
            $baseSeverity += 1;
        }

        // Check for severity keywords
        $severityKeywords = ['severe', 'intense', 'unbearable', 'excruciating', 'extreme', 'terrible', 'awful'];
        foreach ($severityKeywords as $keyword) {
            if (strpos(strtolower($symptomText), strtolower($keyword)) !== false) {
                $baseSeverity += 2;
                break;
            }
        }

        return min(10, max(1, $baseSeverity));
    }

    /**
     * Extract categories from matches.
     */
    private function extractCategories($matches)
    {
        $categories = [];
        foreach ($matches as $match) {
            $categories = array_merge($categories, $match['data']['categories']);
        }
        return array_unique($categories);
    }

    /**
     * Normalize text for better matching.
     */
    private function normalizeText($text)
    {
        // Convert to lowercase
        $text = strtolower($text);

        // Remove punctuation
        $text = preg_replace('/[^\w\s]/', ' ', $text);

        // Replace multiple spaces with single space
        $text = preg_replace('/\s+/', ' ', $text);

        // Trim whitespace
        $text = trim($text);

        // Handle common variations
        $replacements = [
            'cant' => 'cannot',
            'dont' => 'do not',
            'wont' => 'will not',
            'im' => 'i am',
            'ive' => 'i have',
            'theres' => 'there is',
            'its' => 'it is',
            'youre' => 'you are',
            'theyre' => 'they are',
            'headaches' => 'headache',
            'pains' => 'pain',
            'aches' => 'ache',
            'hurts' => 'hurt',
            'hurting' => 'hurt',
            'burning' => 'burn',
            'swollen' => 'swelling',
            'painful' => 'pain',
            'aching' => 'ache',
            'throbbing' => 'throb',
            'stabbing' => 'stab',
            'cramping' => 'cramp',
            'tingling' => 'tingle',
            'numbness' => 'numb',
            'weakness' => 'weak',
            'dizziness' => 'dizzy',
            'nauseous' => 'nausea',
            'vomiting' => 'vomit',
            'coughing' => 'cough',
            'sneezing' => 'sneeze',
            'itching' => 'itch',
            'bleeding' => 'bleed',
            'sweating' => 'sweat',
            'shaking' => 'shake',
            'trembling' => 'tremble'
        ];

        foreach ($replacements as $from => $to) {
            $text = str_replace($from, $to, $text);
        }

        return $text;
    }

    /**
     * Get default analysis for unmatched symptoms.
     */
    private function getDefaultAnalysis($symptomText = '')
    {
        // Try to provide some basic analysis even for unmatched symptoms
        $diagnosis = 'I understand you\'re experiencing symptoms. ';
        $urgency = 3;
        $severity = 3;
        $categories = ['General'];
        $recommendations = [
            'Monitor your symptoms',
            'Stay hydrated and get adequate rest',
            'Consider consulting a healthcare provider if symptoms persist'
        ];

        // Basic keyword analysis for common symptoms
        $lowerSymptom = strtolower($symptomText);

        if (strpos($lowerSymptom, 'pain') !== false || strpos($lowerSymptom, 'hurt') !== false || strpos($lowerSymptom, 'ache') !== false) {
            $diagnosis .= 'You mentioned pain, which can have many causes. ';
            $urgency = 4;
            $categories[] = 'Pain';
            $recommendations[] = 'Apply appropriate pain relief measures';
        }

        if (strpos($lowerSymptom, 'fever') !== false || strpos($lowerSymptom, 'hot') !== false) {
            $diagnosis .= 'Fever often indicates your body is fighting an infection. ';
            $urgency = 4;
            $categories[] = 'Infection';
        }

        if (strpos($lowerSymptom, 'head') !== false || strpos($lowerSymptom, 'skull') !== false) {
            $diagnosis .= 'Headaches can be caused by stress, dehydration, or other factors. ';
            $urgency = 4;
            $categories[] = 'Neurological';
            $recommendations[] = 'Rest in a quiet, dark room';
            $recommendations[] = 'Apply cold or warm compress';
        }

        if (strpos($lowerSymptom, 'stomach') !== false || strpos($lowerSymptom, 'nausea') !== false) {
            $diagnosis .= 'Digestive symptoms can be related to diet, stress, or illness. ';
            $categories[] = 'Gastrointestinal';
        }

        if (strpos($lowerSymptom, 'cough') !== false || strpos($lowerSymptom, 'throat') !== false) {
            $diagnosis .= 'Respiratory symptoms are often related to infections or allergies. ';
            $categories[] = 'Respiratory';
        }

        $diagnosis .= 'While I can provide general guidance, please consult with a healthcare provider for proper diagnosis and treatment.';

        return [
            'primary_condition' => 'General Symptoms',
            'diagnosis' => $diagnosis,
            'recommended_doctor' => 'General Practitioner',
            'urgency_level' => $urgency,
            'severity' => $severity,
            'categories' => array_unique($categories),
            'confidence' => 50,
            'secondary_conditions' => [],
            'recommendations' => $recommendations,
            'warning_signs' => [
                'Symptoms getting significantly worse',
                'Development of new severe symptoms',
                'High fever (over 101°F)',
                'Difficulty breathing',
                'Severe pain',
                'Signs of infection'
            ]
        ];
    }

    /**
     * Get user's symptom history.
     */
    public function getSymptomHistory(Request $request)
    {
        $user = Auth::user() ?? User::first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $history = SymptomCheck::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }
}
