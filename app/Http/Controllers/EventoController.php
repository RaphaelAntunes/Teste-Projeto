<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventoModel;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;
use OpenApi\Annotations\Info; // Certifique-se de importar a classe Info


class EventoController extends Controller
{   

    // END-POINT PARA CRIAR EVENTOS
    public function criarEvento(Request $request)
{
    $usuarioEmail = Auth::user()->email;
    $dataInicio = $request->input('data_inicio'); // Data de início do novo evento

    // Verifique se já existe um evento com a mesma data de início para o mesmo usuário
    $eventoExistente = EventoModel::where('usr_responsavel', $usuarioEmail)
        ->where('data_inicio', $dataInicio)
        ->first();

    if ($eventoExistente) {
        // Se já existe um evento com a mesma data de início para o mesmo usuário
        return response()->json(['message' => 'Já existe um evento com a mesma data de início'], 400);
    }

    // Verifique se a data de início é um final de semana (sábado ou domingo)
    $dataInicioTimestamp = strtotime($dataInicio);
    $diaDaSemana = date('N', $dataInicioTimestamp);

    if ($diaDaSemana >= 6) {
        return response()->json(['message' => 'Não é permitido registrar eventos em finais de semana'], 400);
    }

    // A data de início é única e não é um final de semana, você pode criar o novo evento
    $evento = EventoModel::create([
        'titulo' => $request->input('titulo'),
        'descricao' => $request->input('descricao'),
        'data_inicio' => $request->input('data_inicio'),
        'data_prazo' => $request->input('data_prazo'),
        'usr_responsavel' => $usuarioEmail,
    ]);

    // Retorne uma resposta de sucesso
    return response()->json(['message' => 'Evento criado com sucesso', 'evento' => $evento], 201);
}


    // END-POINT ABERTO PARA VISUALIZAR TODOS OS EVENTOS CRIADOS

    public function VisualizarEvento()
    {
        $eventos = EventoModel::all();
        return response()->json(['message' => 'Eventos Encontrados', 'eventos' => $eventos], 201);
    }

    public function VisualizarEventoEsp($titulo)
    {
        $evento = EventoModel::where('titulo', $titulo)
        ->first();
        if (!$evento) {
            return response()->json(['message' => 'Evento não encontrado ou você não tem permissão para vê-lo'], 404);
        }

        
        return response()->json(['message' => 'Eventos Encontrados', 'eventos' => $evento], 201);
    }

    // END-POINT PARA EXCLUIR EVENTOS
    public function excluirEvento($titulo)
    {
        // Busque o evento no banco de dados com base no título e usuário autenticado
        $usuarioEmail = Auth::user()->email;
        $evento = EventoModel::where('titulo', $titulo)
            ->where('usr_responsavel', $usuarioEmail)
            ->first();

        if (!$evento) {
            return response()->json(['message' => 'Evento não encontrado ou você não tem permissão para excluí-lo'], 404);
        }

        // Faça a exclusão do evento
        $evento->delete();

        // Retorne uma resposta de sucesso
        return response()->json(['message' => 'Evento deletado com sucesso']);
    }


    // END-POINT PARA EDITAR EVENTOS

    public function editarEvento(Request $request, $titulo)
    {
        // Busque o evento no banco de dados com base no título e usuário autenticado
        $usuarioEmail = Auth::user()->email;
        $evento = EventoModel::where('titulo', $titulo)
            ->where('usr_responsavel', $usuarioEmail)
            ->first();

        if (!$evento) {
            return response()->json(['message' => 'Evento não encontrado ou você não tem permissão para editá-lo'], 404);
        }

        // Atualize os campos do evento com base nos dados fornecidos no request
        $evento->update([
            'titulo' => $request->input('titulo'),
            'descricao' => $request->input('descricao'),
            'data_inicio' => $request->input('data_inicio'),
            'data_prazo' => $request->input('data_prazo'),
            'status' => $request->input('status'),
        ]);

        return response()->json(['message' => 'Evento editado com sucesso']);
    }
}
