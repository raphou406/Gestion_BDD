<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'index</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }
        .card {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin: 10px;
            width: 250px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .card a {
            display: block;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .card a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <h1>Bienvenue sur la gestion des offres</h1>

    <div class="container">
        <div class="card">
            <h3>Ajouter une Offre</h3>
            <p>Formulaire pour ajouter une nouvelle offre.</p>
            <a href="offres/insertion_table.php">Accéder au formulaire</a>
        </div>
        <div class="card">
            <h3>Consulter les Offres</h3>
            <p>Formulaire pour consulter et rechercher des offres existantes.</p>
            <a href="offres/consultation_table.php">Accéder au formulaire</a>
        </div>
    </div>

</body>
</html>
