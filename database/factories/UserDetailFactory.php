<?php

namespace Database\Factories;

use App\Models\UserDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserDetailFactory extends Factory
{
    protected $model = UserDetail::class;

    public function definition(): array
    {
        $disabilities = [
            1  => 'Auditiva - Leve',
            2  => 'Visual - Baixa Visão',
            3  => 'Intelectual - Leve',
            4  => 'Física - Amputado',
            6  => 'Auditiva - Moderada',
            7  => 'Auditiva - Severa',
            8  => 'Auditiva - Profunda',
            9  => 'Intelectual - Moderada',
            10 => 'Intelectual - Severo',
            11 => 'Transtorno Específico da Aprendizagem',
            12 => 'TDAH - Leve',
            13 => 'TDAH - Moderado',
            14 => 'TDAH - Severo',
            15 => 'Transtornos do Espectro Autista - Nível 1',
            16 => 'Transtornos do Espectro Autista - Nível 2',
            17 => 'Transtornos do Espectro Autista - Nível 3',
            18 => 'Síndrome de Down',
            19 => 'Múltiplas',
            20 => 'Física - Paralisia Cerebral',
            22 => 'Física - Hemiplegia',
            23 => 'Física - Hemiparesia',
            24 => 'Física - Monoplegia',
            25 => 'Física - Monoparesia',
            26 => 'Física - Paraplegia',
            27 => 'Física - Paraparesia',
            28 => 'Física - Tetraplegia',
            29 => 'Visual - Monocular',
            30 => 'Visual - Cego',
            31 => 'Visual /Cego - Surdocegueira'
        ];

        $healthIssues = [
            1  => 'Hipertensão Arterial',
            2  => 'Diabetes Mellitus',
            3  => 'Asma',
            4  => 'Doença Pulmonar Obstrutiva Crônica (DPOC)',
            5  => 'Depressão',
            6  => 'Ansiedade',
            7  => 'Obesidade',
            8  => 'Colesterol Alto (Dislipidemia)',
            9  => 'Doença Cardíaca (Cardiopatia)',
            10 => 'Osteoporose',
            11 => 'Artrite Reumatoide',
            12 => 'Alergias',
            13 => 'Enxaqueca Crônica',
            14 => 'Câncer',
            15 => 'Insuficiência Renal Crônica'
        ];

        return [
            'nationality'       => $this->faker->randomElement(['1', '2']),
            'doc_type'          => $this->faker->randomElement(['1', '2']),
            'doc_number'        => $this->faker->unique()->numerify('###########'),
            'phone'             => $this->faker->numerify('###########'),
            'new_number'        => $this->faker->optional()->regexify('[0-9]{32}'),
            'fls'               => $this->faker->optional()->numerify('####'),
            'book'              => $this->faker->optional()->regexify('[A-Z0-9]{1,10}'),
            'old_number'        => $this->faker->optional()->numerify('######'),
            'municipality'      => $this->faker->city(),
            'zip'               => $this->faker->numerify('########'),
            'street'            => $this->faker->streetName(),
            'number'            => $this->faker->optional()->buildingNumber(),
            'complement'        => $this->faker->optional()->word(),
            'burgh'             => $this->faker->word(),
            'city'              => $this->faker->city(),
            'state'             => $this->faker->state(),
            'school_name'       => $this->faker->company(),
            'school_city'       => $this->faker->city(),
            'school_state'      => $this->faker->state(),
            'school_year'       => $this->faker->year(),
            'school_ra'         => $this->faker->unique()->bothify('RA########'),
            'mother'            => $this->faker->name('female'),
            'mother_phone'      => $this->faker->optional()->numerify('###########'),
            'father'            => $this->faker->optional()->name('male'),
            'father_phone'      => $this->faker->optional()->numerify('###########'),
            'responsible'       => $this->faker->optional()->name(),
            'degree'            => $this->faker->optional()->randomElement(['1', '2', '3']),
            'kinship'           => $this->faker->optional()->word(),
            'responsible_phone' => $this->faker->optional()->numerify('###########'),
            'parents_email'     => $this->faker->safeEmail(),
            'health'            => $this->faker->optional()->randomElement($healthIssues),
            'accessibility'     => $this->faker->optional()->randomElement($disabilities),
            'nis'               => $this->faker->optional()->numerify('###########'),
        ];
    }
}
