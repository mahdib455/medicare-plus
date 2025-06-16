<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medication;

class MedicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medications = [
            [
                'name' => 'Paracétamol',
                'generic_name' => 'Paracétamol',
                'brand_name' => 'Doliprane',
                'description' => 'Antalgique et antipyrétique',
                'category' => 'Antalgique',
                'unit' => 'comprimé',
                'strength' => '500mg',
                'form' => 'comprimé',
                'manufacturer' => 'Sanofi',
                'active_ingredient' => 'Paracétamol',
                'contraindications' => 'Insuffisance hépatique sévère',
                'side_effects' => 'Rares: réactions allergiques',
                'storage_conditions' => 'Conserver à température ambiante',
                'is_active' => true,
            ],
            [
                'name' => 'Ibuprofène',
                'generic_name' => 'Ibuprofène',
                'brand_name' => 'Advil',
                'description' => 'Anti-inflammatoire non stéroïdien',
                'category' => 'AINS',
                'unit' => 'comprimé',
                'strength' => '200mg',
                'form' => 'comprimé',
                'manufacturer' => 'Pfizer',
                'active_ingredient' => 'Ibuprofène',
                'contraindications' => 'Ulcère gastroduodénal, insuffisance rénale',
                'side_effects' => 'Troubles digestifs, maux de tête',
                'storage_conditions' => 'Conserver à température ambiante',
                'is_active' => true,
            ],
            [
                'name' => 'Amoxicilline',
                'generic_name' => 'Amoxicilline',
                'brand_name' => 'Clamoxyl',
                'description' => 'Antibiotique pénicilline',
                'category' => 'Antibiotique',
                'unit' => 'gélule',
                'strength' => '500mg',
                'form' => 'gélule',
                'manufacturer' => 'GSK',
                'active_ingredient' => 'Amoxicilline',
                'contraindications' => 'Allergie aux pénicillines',
                'side_effects' => 'Troubles digestifs, réactions allergiques',
                'storage_conditions' => 'Conserver au frais',
                'is_active' => true,
            ],
            [
                'name' => 'Oméprazole',
                'generic_name' => 'Oméprazole',
                'brand_name' => 'Mopral',
                'description' => 'Inhibiteur de la pompe à protons',
                'category' => 'Gastro-entérologie',
                'unit' => 'gélule',
                'strength' => '20mg',
                'form' => 'gélule',
                'manufacturer' => 'AstraZeneca',
                'active_ingredient' => 'Oméprazole',
                'contraindications' => 'Hypersensibilité au produit',
                'side_effects' => 'Maux de tête, troubles digestifs',
                'storage_conditions' => 'Conserver à température ambiante',
                'is_active' => true,
            ],
            [
                'name' => 'Loratadine',
                'generic_name' => 'Loratadine',
                'brand_name' => 'Clarityne',
                'description' => 'Antihistaminique H1',
                'category' => 'Antihistaminique',
                'unit' => 'comprimé',
                'strength' => '10mg',
                'form' => 'comprimé',
                'manufacturer' => 'Bayer',
                'active_ingredient' => 'Loratadine',
                'contraindications' => 'Hypersensibilité au produit',
                'side_effects' => 'Somnolence, sécheresse buccale',
                'storage_conditions' => 'Conserver à température ambiante',
                'is_active' => true,
            ],
            [
                'name' => 'Salbutamol',
                'generic_name' => 'Salbutamol',
                'brand_name' => 'Ventoline',
                'description' => 'Bronchodilatateur',
                'category' => 'Pneumologie',
                'unit' => 'dose',
                'strength' => '100µg/dose',
                'form' => 'aérosol',
                'manufacturer' => 'GSK',
                'active_ingredient' => 'Salbutamol',
                'contraindications' => 'Hypersensibilité au produit',
                'side_effects' => 'Tremblements, palpitations',
                'storage_conditions' => 'Conserver à température ambiante',
                'is_active' => true,
            ],
            [
                'name' => 'Metformine',
                'generic_name' => 'Metformine',
                'brand_name' => 'Glucophage',
                'description' => 'Antidiabétique oral',
                'category' => 'Endocrinologie',
                'unit' => 'comprimé',
                'strength' => '850mg',
                'form' => 'comprimé',
                'manufacturer' => 'Merck',
                'active_ingredient' => 'Metformine',
                'contraindications' => 'Insuffisance rénale, acidose métabolique',
                'side_effects' => 'Troubles digestifs, goût métallique',
                'storage_conditions' => 'Conserver à température ambiante',
                'is_active' => true,
            ],
            [
                'name' => 'Atorvastatine',
                'generic_name' => 'Atorvastatine',
                'brand_name' => 'Tahor',
                'description' => 'Hypolipémiant',
                'category' => 'Cardiologie',
                'unit' => 'comprimé',
                'strength' => '20mg',
                'form' => 'comprimé',
                'manufacturer' => 'Pfizer',
                'active_ingredient' => 'Atorvastatine',
                'contraindications' => 'Maladie hépatique active',
                'side_effects' => 'Douleurs musculaires, troubles digestifs',
                'storage_conditions' => 'Conserver à température ambiante',
                'is_active' => true,
            ],
        ];

        foreach ($medications as $medication) {
            Medication::create($medication);
        }
    }
}
