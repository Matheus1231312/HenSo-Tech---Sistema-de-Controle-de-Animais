<?php
require 'config.php';

// Inclui o autoloader da Google API (ajuste o caminho se necessário)
require_once __DIR__ . '/google-api/vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $animal_id = intval($_POST['animal_id'] ?? 0);
    $data_inseminacao = $_POST['data_inseminacao'] ?? '';
    $touro = $_POST['touro'] ?? '';
    $raca_touro = $_POST['raca_touro'] ?? null;
    $tipo_inseminacao = $_POST['tipo_inseminacao'] ?? 'Normal';

    if ($animal_id <= 0 || empty($data_inseminacao)) {
        die('Dados inválidos.');
    }

    // === INSERIR NO BANCO DE DADOS ===
    try {
        $sql = "INSERT INTO prenhezes 
                (animal_id, data_inseminacao, touro, raca_touro, tipo_inseminacao, status) 
                VALUES (?, ?, ?, ?, ?, 'EmGestacao')";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$animal_id, $data_inseminacao, $touro, $raca_touro, $tipo_inseminacao]);

        // Pega o nome do animal para usar no evento
        $stmt = $pdo->prepare("SELECT nome, numero FROM animais WHERE id = ?");
        $stmt->execute([$animal_id]);
        $animal = $stmt->fetch();

        if (!$animal) {
            die('Animal não encontrado.');
        }

        $nome_animal = $animal['nome'] . ($animal['numero'] ? " ({$animal['numero']})" : '');

    } catch (Exception $e) {
        error_log('Erro ao inserir prenhez: ' . $e->getMessage());
        die('Erro ao registrar a prenhez.');
    }

    // === INTEGRAÇÃO COM GOOGLE CALENDAR ===
    $caminhoJson = __DIR__ . '/service-account-key.json';
    if (!file_exists($caminhoJson)) {
        error_log('Arquivo service-account-key.json não encontrado.');
    } else {
        putenv("GOOGLE_APPLICATION_CREDENTIALS=$caminhoJson");

        $client = new Google_Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Google_Service_Calendar::CALENDAR_EVENTS);

        try {
            $service = new Google_Service_Calendar($client);

            // ID do calendário (mude se necessário)
            $calendarId = '939db53fac90526475c3babb8446a9c0ef867d6372445b3c97f2356688c76da9@group.calendar.google.com';

            $data_ins = new DateTime($data_inseminacao);

            // Evento: Secagem (+7 meses)
            $data_secagem = clone $data_ins;
            $data_secagem->modify('+7 months');

            $event = new Google_Service_Calendar_Event([
                'summary' => "Secar vaca: $nome_animal",
                'description' => "Secar aos 7 meses de gestação.\nInseminação: " . $data_ins->format('d/m/Y') . "\nTouro: $touro",
                'start' => ['date' => $data_secagem->format('Y-m-d')],
                'end' => ['date' => $data_secagem->format('Y-m-d')],
                'colorId' => '6', // Cor laranja (opcional)
            ]);
            $service->events->insert($calendarId, $event);

            // Evento: Período de parto (+9 meses a +9 meses +7 dias)
            $data_prevista_parto = clone $data_ins;
            $data_prevista_parto->modify('+9 months');

            $inicio_parto = clone $data_prevista_parto;
            $inicio_parto-> modify('-5 days');

            $fim_parto = clone $inicio_parto;
            $fim_parto->modify('+10 days');

            $event = new Google_Service_Calendar_Event([
                'summary' => "Parto previsto: $nome_animal",
                'description' => "Janela de parto (7 dias).\nInseminação: " . $data_ins->format('d/m/Y') . "\nTouro: $touro",
                'start' => ['date' => $inicio_parto->format('Y-m-d')],
                'end' => ['date' => $fim_parto->format('Y-m-d')],
                'colorId' => '11', // Cor vermelha (opcional)
            ]);
            $service->events->insert($calendarId, $event);

        } catch (Exception $e) {
            error_log('Erro Google Calendar: ' . $e->getMessage());
            // Não interrompe o cadastro mesmo se falhar o calendário
        }
    }

    // Redireciona para a página do animal
    header("Location: ver_animal.php?id=$animal_id");
    exit;
}

// Se não for POST, redireciona
header('Location: lancar_inseminacao.php');
exit;
?>