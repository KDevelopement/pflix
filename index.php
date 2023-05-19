<!DOCTYPE html>
<?php
  $Items = [];
  $ReadJSON = file_get_contents("date.json");
  $ItemsJson = json_decode($ReadJSON);
  if ($ItemsJson) {
    foreach ($ItemsJson AS $Item) {
      $JD["id"] =  $Item->id;
      $JD["cover"] =  $Item->cover;
      $JD["name"] =  $Item->name;
      $JD["url"] =  $Item->url;
      foreach ($Item->date AS $date) {
        $D[] = [
          "max_eps" => $date->max_eps,
        ];
        $ResultCount = $D;
      }
      $JD["date"] = $ResultCount;
      $JD["status"] =  $Item->status;
      $Items[] = $JD;
    }
  }

  $List = FALSE;
  $id = isset($_GET['id']) ? $_GET['id'] : null;
  if (!empty($id)) {
    if (!isset($_GET['t']) AND !isset($_GET['e']) OR empty($_GET['t']) AND empty($_GET['e'])) {
      $T = 1;
      $E = 1;
    } else {
      $T = $_GET['t'];
      $E = $_GET['e'];
    }
    $Result = [];
    foreach ($Items AS $Item) {
      if(in_array($id, $Item)) {
        $I["id"] = $Item["id"];  
        $I["name"] = $Item["name"];  
        $I["url"] = $Item["url"];
        $count = 0;
        foreach ($Item["date"] AS $date) {
          $count++;
          $D[] = [
            "id" => $count,
            "max" => $date["max_eps"],
          ];
          $ResultCount = $D;
        }
        $I["date"] = $ResultCount;
        $Result = $I;
      }
    }
  } else {
    $List = TRUE;
  }
?>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>pFlix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  </head>
  <body>
    <?php if ($List) { ?>
      <main>
        <div class="container my-5 rounded-3 border shadow-lg">
          <div class="row align-items-center">
            <div class="col-lg-12 text-center py-2">
              <h1>pFlix</h1>
              <h3>Lista de series e filmes.</h3>
              <small class="text-danger">Não hospedamos series/filmes, apenas indexamos.</small>
            </div>
          </div>
          <hr />
          <div class="row align-items-center px-3 pb-3">
            <?php if ($Items) { ?>
              <?php foreach ($Items AS $Item) { ?>
                <div class="col-lg-4 text-center py-2">
                  <div class="card" style="width: 18rem;">
                    <img src="<?= $Item["cover"]; ?>" class="card-img-top" alt="<?= $Item["name"]; ?>">
                    <div class="card-body">
                      <h5 class="card-title"><?= $Item["name"]; if($Item["status"]) { ?> {Assistiu} <?php } ?></h5>
                      <a href="?id=<?= $Item["id"]; ?>&t=1&e=1" class="btn btn-primary w-100">
                        Assistir
                      </a>
                    </div>
                  </div>
                </div>
              <?php } ?>
            <?php } else { ?>
              <div class="col-lg-12 text-center py-2">
                <h1>Oop's</h1>
                <h3>Não temos nenhuma serie ou filme listado no arquivo date.json!</h3>
                <p>
                  Para adicionar use o formato "JSON" a baixo (Exemplo):
                </p>
              </div>
              <div class="col-lg-8 mx-auto pb-2">
                <blockquote>
                  <code>
                    <pre class="bg-warning border h-80">
                    [
                        {
                            "id": 1,
                            "cover": "https://pbs.twimg.com/media/FNabcnsWUAI4Uw6.jpg:large",
                            "name": "Herdeiros da Noite",
                            "url": "https://souapenasumsitenormal.com/player/tv/93842/{temporada}/{episodio}/dub",
                            "date": [
                                {"max_eps": 13 },
                                {"max_eps": 13 }
                            ],
                            "status": true
                        }
                    ]
                      </pre>
                  </code>
                </blockquote>
              </div>
            <?php } ?>
          </div>
        </div>
      </main>
    <?php } else { ?>
      <main>
        <div class="container my-5 rounded-3 border shadow-lg">
          <div class="row align-items-center">
            <div class="col-lg-12 text-center py-2">
              <h1><?= $Result["name"]; ?></h1>
              <h3><?= "Temp.:" . " " . $T . " " . "&" . " " . "Eps.:" . " " . $E; ?></h3>
              <small class="text-danger">Não hospedamos series/filmes, apenas indexamos.</small>
            </div>
            <hr />
            <div class="col-lg-12 p-4">
              <?php $url  = str_replace(['{temporada}', '{episodio}'], [$T, $E], $Result["url"]); ?>
              <iframe src="<?= $url; ?>" style="border:2px solid red; frameborder=" 0"="" height="435" scrolling="no" width="100%" allowfullscreen="" mozallowfullscreen="" msallowfullscreen="" oallowfullscreen="" webkitallowfullscreen=""></iframe>
            </div>
          </div>
          <hr />
          <div class="row px-5 mb-2">
            <?php 
              if ($I["date"]) { 
                foreach ($Result["date"] as $Date) {
                  if ($T >= $Date["id"]) {
                    ?>
                      <div class="col-4 py-1">
                        <a class="btn btn-danger btn-sm px-4 d-block w-100 me--md-2 fw-bold disabled" disabled href="#!">
                          TEMPORADA <?= $Date["id"]; ?>
                        </a>
                      </div>
                    <?php  } else { ?>
                      <div class="col-4 py-1">
                        <a class="btn btn-danger btn-sm px-4 d-block w-100 me--md-2 fw-bold" href="?id=<?= $id ?>&t=<?= $Date["id"]; ?>&e=1">
                          TEMPORADA <?= $Date["id"]; ?>
                        </a>
                    </div>
                    <?php  
                  }
                }
              }
            ?>
          </div>
          <hr />
          <div class="row px-5 mb-2">
            <?php 
              if ($I["date"]) { 
                foreach ($Result["date"] as $Date) {
                  if ($T == $Date["id"]) {
                    for ($i = 1; $i <= $Date["max"]; $i++) {
                      if ($E >= $i) {
                    ?>
                      <div class="col-4 py-1">
                        <a class="btn btn-danger btn-sm px-4 d-block w-100 me--md-2 fw-bold disabled" disabled href="#!">
                          EPISODIO <?= $i; ?>
                        </a>
                      </div>
                    <?php 
                      } else{
                    ?>
                      <div class="col-4 py-1">
                        <a class="btn btn-danger btn-sm px-4 d-block w-100 me--md-2 fw-bold" href="?id=<?= $id ?>&t=<?= $Date["id"]; ?>&e=<?= $i; ?>">
                          EPISODIO <?= $i; ?>
                        </a>
                      </div>
                    <?php 
                      }
                    }
                  }
                }
              }
            ?>
          </div>
        </div>
      </main>
    <?php } ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  </body>
</html>