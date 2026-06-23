<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            "EM ALCIONE AP FERNANDES PEREIRA",
            "EM ALFREDO DONAIRE",
            "EM ANDRÉ DE NADAI",
            "EM ARCO ÍRIS",
            "EM BORBOLETINHA AZUL",
            "EM DO CAIC ANDRÉ DE NADAI",
            "EM JARDIM BOM RETIRO",
            "EM JARDIM DENADAI",
            "EM JARDIM LÚCIA",
            "EM JARDIM MARIA ANTONIA",
            "EM JARDIM SÃO JUDAS TADEU",
            "EM JOSÉ DE ANCHIETA",
            "EM LASQUINHA DE GENTE",
            "EM MAGDALENA MARIA VEDOVATTO CALLEGARI",
            "EM MUNDO ALEGRE DA CRIANÇA",
            "EM OSWALDO RONCOLATTO",
            "EM PALHACINHO DENGOSO",
            "EM PARQUE BANDEIRANTES II",
            "EM PARQUE DAS NAÇÕES",
            "EM PARQUE RESIDENCIAL REGINA",
            "EM PROF MARTHA SMOLII DOMINGUES",
            "EM RAMONA CANHETE PINTO",
            "EM REINO DA GAROTADA",
            "EM SABIDINHO",
            "EM SANTO TOMAZIN",
            "EM VISCONDE DE SABUGOSA",
            "EM XODÓ DA TITIA",
            "EMEF ANTONIETTA CIA VIEL",
            "EMEF ANTONIO PALIOTO",
            "EMEF PROF ANÁLIA DE O. NASCIMENTO",
            "EMEF PROF ELIANA MINCHIN VAUGHAN",
            "EMEF PROF FLORA FERREIRA GOMES",
            "EMEF PROF NEUSA DE SOUZA CAMPOS",
            "EMEF PROF NILZA THOMAZINI",
            "EMEFr D AUGUSTA RAVAGNANI BASSO",
            "EMEFr MARIA APARECIDA DE JESUS SEGURA",
            "EM MARIA LUIZA CIA MEDEIROS",
            "EM JEANY LEMOS GONÇALVES RODRIGUES (RES. SANTA JOANA)",
            "EM DIRCE APARECIDA MENUZZO RICARDO (JARDIM DAS ESTÂNCIAS)"
        ];

        foreach ($schools as $name) {
            School::updateOrCreate(
                ['name' => $name],  // 1º parâmetro: procura por isso
                ['name' => $name]   // 2º parâmetro: cria/atualiza com isso
            );
        }
    }
}