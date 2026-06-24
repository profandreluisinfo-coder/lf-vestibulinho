<?php

namespace Database\Seeders;

use App\Models\Resource;
use Illuminate\Database\Seeder;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $resources = [
            ['description' => 'Prova ampliada (fonte tamanho 20)'],
            ['description' => 'Prova em braile'],
            ['description' => 'Auxílio para leitura da prova'],
            ['description' => 'Auxílio para transcrição das respostas'],
            ['description' => 'Intérprete de Libras'],
            ['description' => 'Tempo adicional para realização da prova'],
            ['description' => 'Mesa adaptada para cadeira de rodas'],
            ['description' => 'Uso de equipamento médico'],
            ['description' => 'Permissão para uso de aparelho auditivo'],
            ['description' => 'Permissão para uso de medicação durante a prova'],
            ['description' => 'Acompanhamento de ledor'],
            ['description' => 'Apoio para mobilidade'],
            ['description' => 'Ambiente com menor estímulo sonoro']
        ];

        foreach ($resources as $resource) {
            Resource::updateOrCreate($resource);
        }
    }
}
