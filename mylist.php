<link rel="stylesheet" href="assets/css/mylist.css">
<?php
include_once('navbar.php');
require_once('connection.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: register.php");
    exit();
}

?>

<!-- <?php



?>

<h1 class="user_email_naslov"><?php $_SESSION['user_email']?></h1> -->

<?php

$user_id = $_SESSION['user_id'];

$query = "SELECT sp.spisak_id, sp.naziv, sp.opis, GROUP_CONCAT(t.task_text) as tasks
          FROM spiskovi sp
          LEFT JOIN taskovi t ON sp.spisak_id = t.spisak_id
          WHERE sp.user_id = ?
          GROUP BY sp.spisak_id";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Moji Spiskovi</title>
    <style>
       table {
    max-width: 80%; /* Postavite maksimalnu širinu tabele prema vašim potrebama */
    margin: 0; /* Uklonite spoljni margin */
}

tr {
    display: flex; /* Koristite fleksibilan model raspoređivanja za redove */
    justify-content: flex-start; /* Poravnajte redove sa levom stranom */
    margin-bottom: 10px; /* Dodajte razmak između redova */
}

td {
    border: 1px solid #000;
    padding: 8px;
    text-align: left;
    min-width: 200px; /* Postavite minimalnu širinu za ćelije */
    background-color: #f2f2f2; /* Pozadinska boja za svaku ćeliju */
    margin-right: 10px; /* Dodajte razmak između ćelija */
}

/* Postavite različite pozadinske boje za neparno i parno obojene redove */
tr:nth-child(odd) td {
    background-color: #f0f0f0;
}

tr:nth-child(even) td {
    background-color: #e0e0e0;
}

.underline {
    text-decoration: line-through;
}

@media (max-width: 768px) {
    table {
        font-size: 14px;
    }
    th, td {
        padding: 6px;
    }
    .actions {
        text-align: center;
        position: sticky;
        bottom: 0;
        background-color: white;
        box-shadow: 0px -2px 5px rgba(0, 0, 0, 0.2);
        padding: 10px;
    }
}

@media (min-width: 768px) {
    .actions {
        text-align: right;
        position: fixed;
        right: 20px;
        bottom: 20px;
    }
}
    </style>
</head>
<body>
    <h1>Moji Spiskovi</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Naziv</th>
            <th>Opis</th>
            <th>Zadaci</th>
            <!-- <th>Datum i vreme dodavanja</th> -->
        </tr>
        <?php
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $row['naziv'] ?></td>
            <td><?= $row['opis'] ?></td>
            <td><?= $row['tasks'] ?></td>
        </tr>
        <?php
        endwhile;
        ?>
    </table>
    <div id="actions">
        <a href="create_list.php">Kreiraj novi spisak</a>
        <a href="logout.php">Odjavi se</a>
    </div>

    <script>
        var taskTextElements = document.querySelectorAll('.task-text');

        taskTextElements.forEach(function (element) {
            element.addEventListener('click', function () {
                if (element.classList.contains('underline')) {
                    element.classList.remove('underline');
                } else {
                    element.classList add('underline');
                }
            });
        });
    </script>
</body>
</html>
