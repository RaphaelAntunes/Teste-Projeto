<!DOCTYPE html>
<html lang="pt-br">
<head>
    <!-- Cabeçalho HTML -->
</head>
<body>
    <div class="container">
        <!-- Formulário para editar evento -->
        <h1>Editar Evento</h1>
        <form action="/eventos/{{ $evento->titulo }}" method="POST">
            @csrf
            @method('PUT')
            <label for="titulo">Título</label>
            <input type="text" name="titulo" value="{{ $evento->titulo }}" required>
            <label for="descricao">Descrição</label>
            <textarea name="descricao" required>{{ $evento->descricao }}</textarea>
            <label for="data_inicio">Data de Início</label>
            <input type="date" name="data_inicio" value="{{ $evento->data_inicio }}" required>
            <label for="data_prazo">Data de Encerramento</label>
            <input type="date" name="data_prazo" value="{{ $evento->data_prazo }}" required>
            <button type="submit">Salvar</button>
        </form>
    </div>
</body>
</html>
