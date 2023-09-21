<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\EventoModel;


class App extends TestCase
{
    /**
     *
     *
     * @return void
     */
    public function test_user_registration()
    {
        $response = $this->post('http://localhost:8000/register', [
            'name' => 'Johnss Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
    
        $response->assertStatus(302);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('token-name')->plainTextToken;
    
            return response()->json(['token' => $token], 200);
        }
    
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function test_user_login()
    {
        $response = $this->post('http://localhost:8000/login', [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);
    
        $response->assertStatus(302);
    
        $response->assertCookie('laravel_session');
    
        $token = $response->cookie('laravel_session');
    
        return $token;
    }

    public function test_create_event()
    {
        $token = $this->test_user_login();

        $response = $this->json('POST', 'http://localhost:8000/criar-evento', [
            'title' => 'Exemplo de Título',
            'description' => 'Exemplo de Descrição',
            'start' => '2023-09-20',
            'end' => '2023-09-30',
            'usr_responsavel' => 'john@example.com',
        ], ['Authorization' => "Bearer $token"]);

        $response->assertStatus(302);
        
        // Fetch the created event from the database
        $event = Evento::where([
            'title' => 'Exemplo de Título',
            'description' => 'Exemplo de Descrição',
            'start' => '2023-09-20',
            'end' => '2023-09-30',
            'usr_responsavel' => 'john@example.com',
        ])->first();

        // Assert that the event was created in the database
        $this->assertNotNull($event);
    }

    public function test_update_event()
    {
        $token = $this->test_user_login();

        // Create an event
        $event = EventoModel::create([
            'title' => 'Evento para Edição',
            'description' => 'Descrição do Evento',
            'start' => '2023-09-20',
            'end' => '2023-09-30',
            'usr_responsavel' => 'john@example.com',
        ]);

        $data = [
            'title' => 'Evento Editado',
            'description' => 'Descrição Editada',
            'start' => '2023-11-01',
            'end' => '2023-11-10',
            'usr_responsavel' => 'john@example.com',
        ];

        $response = $this->put("http://localhost:8000/editar-evento/{$event->id}", $data, ['Authorization' => "Bearer $token"]);

        $response->assertStatus(302);

        // Reload the event from the database to get the updated attributes
        $event->refresh();

        // Assert that the event was updated in the database
        $this->assertEquals($data['title'], $event->title);
        $this->assertEquals($data['description'], $event->description);
        $this->assertEquals($data['start'], $event->start->format('Y-m-d'));
        $this->assertEquals($data['end'], $event->end->format('Y-m-d'));
        $this->assertEquals($data['usr_responsavel'], $event->usr_responsavel);
    }

    public function test_delete_event()
    {
        $token = $this->test_user_login();

        // Create an event
        $event = EventoModel::create([
            'title' => 'Evento para Exclusão',
            'description' => 'Descrição do Evento',
            'start' => '2023-09-20',
            'end' => '2023-09-30',
            'usr_responsavel' => 'john@example.com',
        ]);

        $response = $this->delete("http://localhost:8000/excluir-evento/{$event->id}", [], ['Authorization' => "Bearer $token"]);

        $response->assertStatus(302);

        // Assert that the event was deleted from the database
        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }
}
