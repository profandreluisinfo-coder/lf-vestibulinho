<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            'EM JOSÉ DE ANCHIETA',
            'EMEF ANTONIO PALIOTO',
            'EMEF NILZA THOMAZINI',
            'JARDIM DAS ORQUÍDEAS',
            'ANGELO CAMPO DALL ORTO',
            'JOAO FRANCESCHINI',
            'WADIH JORGE MALUF',
            'CECILIA DE NEGRI PROFESSORA',
            'SOLANGE MAURA ALBINO',
            'LUIS HENRIQUE MARCHI PROFESSOR',
            'ZORAIDE PROENCA KAYSEL PROFESSORA',
            'VITO CARMINE CERBASI PROFESSOR',
            'MARIA CHEILA ALVES PROFESSORA',
            'ANTONIO DO VALLE SOBRINHO',
            'MARIA DE LOURDES MARTINS PROFESSORA',
            'ELYSABETH DE MELLO RODRIGUES PROFESSORA',
            'MARIANINA DE ROSIS MORAES PROFESSORA',
            'JENY BONADIA RODRIGUES SANTARROSSA PROFESSORA',
            'MANUEL ALBALADEJO FERNANDES',
            'ALICE ANTENOR DE SOUZA PROFESSORA',
            'ANA LUCIA PIERINI PROFESSORA',
            'LEILA MARA AVELINO PROFESSORA',
            'RESIDENCIAL BORDON',
            'WANDA FELIX DE ANDRADE PROFESSORA',
            'ONDINA PINTO GONZALEZ PROFESSORA',
            'MARIA IVONE MARTINS ROSA PROFESSORA',
            'JOSE MIRANDA PREFEITO',
            'LEONILDA ROSSI BARRIQUELO PROFESSORA',
            'BELGICA ALLEONI BORGES PROFESSORA',
            'SAVINO CAMPIGLI',
            'MARINALVA GIMENES COLOSSAL DA CUNHA',
            'SONIA MARIA MASCHIO BAPTISTA PROFESSORA'
        ];

        foreach ($schools as $name) {
            School::updateOrCreate(
                ['name' => trim($name)],  // 1º parâmetro: procura por isso
                ['name' => trim($name)]   // 2º parâmetro: cria/atualiza com isso
            );
        }
    }
}
