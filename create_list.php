<!DOCTYPE html>
<html>
<head>
    <title>Kreiraj spisak</title>
    <link rel="stylesheet" type="text/css" href="assets/css/create_list.css"> <!-- Dodajte link za stilove -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>


<?php
include_once('navbar.php');
require_once('connection.php');
session_start();

if (isset($_SESSION['user_id'])) {
    echo "<p class='text'>Dobrodošli na stranicu gde možete napraviti svoj spisak.</p><br>";
    echo "<a href='logout.php'>Odjavite se</a>";
} else {
    header("Location: register.php");
    exit();
}

if (isset($_POST['potvrdi'])) {
    $naziv = $_POST['naziv'];
    $opis = $_POST['opis'];
    $user_id = $_SESSION['user_id']; // Dohvatanje user_id iz sesije

    $query = "INSERT INTO spiskovi (user_id, naziv, opis) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iss', $user_id, $naziv, $opis);
    $stmt->execute();
    $spisak_id = $conn->insert_id; // ID poslednjeg ubačenog spiska

    // Prikupljanje taskova
    $taskovi = array();

    foreach ($_POST as $key => $value) {
        if (strpos($key, 'task') === 0) {
            if (!empty($value)) {
                $taskovi[] = $value;
            }
        }
    }

    // Ubacivanje taskova u tabelu taskovi
    $query = "INSERT INTO taskovi (spisak_id, task_text) VALUES (?, ?)";
    $stmt = $conn->prepare($query);

    foreach ($taskovi as $task) {
        $stmt->bind_param('is', $spisak_id, $task);
        $stmt->execute();
    }

    // Zatvaranje konekcije sa bazom podataka
    $stmt->close();
    $conn->close();

    // Redirect na my_list.php nakon dodavanja spiska
    header("Location: mylist.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kreiraj spisak</title>
</head>
<body>
    <h1>Kreiraj spisak</h1>
    <form method="post" action="">
    <label for="Naziv spiska">Naziv spiska:</label>
    <input type="text" id="naziv" name="naziv" required><br><br>
    
    <label for="Opis spiska">Opis spiska: (Opciono)</label>
    <input type="text" id="opis" name="opis"><br><br>
    
    <div id="task_container">
        <label for="task1">Task 1:</label>
        <input type="text" id="task1" name="task1" required><br>
    </div>
    
    <button type="button" id="dodaj_task">Dodaj</button>
    <button type="button" id="obrisi_task">Obriši</button>
    
    <input type="submit" name="potvrdi" value="Potvrdi">
</form>

<script>
    document.getElementById('dodaj_task').addEventListener('click', function() {
        var brojTaskova = document.querySelectorAll('input[name^="task"]').length + 1;
        var novoPolje = document.createElement('div');
        var label = document.createElement('label');
        label.innerHTML = 'Task ' + brojTaskova + ': ';
        var inputTask = document.createElement('input');
        inputTask.type = 'text';
        inputTask.name = 'task' + brojTaskova; 
        inputTask.required = true;
        novoPolje.appendChild(label);
        novoPolje.appendChild(inputTask);
        document.getElementById('task_container').appendChild(novoPolje);
    });

    document.getElementById('obrisi_task').addEventListener('click', function() {
        var taskContainer = document.getElementById('task_container');
        var brojTaskova = document.querySelectorAll('input[name^="task"]').length;

        if (brojTaskova > 1) {
            // Ako ima više od jednog taska, obriši poslednji
            taskContainer.removeChild(taskContainer.lastChild);
        } else {
            alert("Ne možete obrisati poslednji task.");
        }
    });
</script>

</body>
</html>
