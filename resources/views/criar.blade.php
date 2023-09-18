<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Cabeçalho HTML -->
</head>
<body>
    <div class="container">
        <!-- Formulário para criar evento -->
        <h1>Adicionar Evento</h1>
        <form action="/criar-evento" method="POST">
            @csrf
            <label for="titulo">Título</label>
            <input type="text" name="titulo" required>
            <label for="descricao">Descrição</label>
            <textarea name="descricao" required></textarea>
            <label for="data_inicio">Data de Início</label>
            <input type="date" name="data_inicio" required>
            <label for="data_prazo">Data de Encerramento</label>
            <input type="date" name="data_prazo" required>
            <button type="submit">Adicionar</button>
        </form>
    </div>
</body>
</html>
