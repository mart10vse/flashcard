<?php
require "vendor/autoload.php";
session_start();

if (isset($_SESSION["flash"]))
{
    vprintf('<button type="button" class="btn btn-%s" disabled>%s</button>', $_SESSION["flash"]);
    unset($_SESSION["flash"]);
}

?>

<?php require './incl/header.php' ?>
  <main class="px-md-4 m-5">
    <ul class="list-group" id="deck-list"></ul>
    <div class="container" style="max-width:75vw">
      <div class="d-flex justify-content-center align-items-center mb-2">
        <button class="btn btn-secondary mx-2" id="shuffle">Shuffle</button>
        <button class="btn btn-secondary" id="clear">Clear</button>
      </div>
      <div class="d-flex justify-content-between">
        <button class="btn btn-secondary" id="previous" style="min-width:5vw">Previous</button>
        <div class="d-flex justify-content-center align-items-center h-100">
          <div class="card flip-card">
            <div class="flip-card-inner">
              <div class="d-flex align-items-center justify-content-center flip-card-front">
                <p class="card-title" id="card-term"></p>
              </div>
              <div class="d-flex align-items-center justify-content-center flip-card-back" style="overflow:auto">
                <p class="card-title" id="card-def"></p>
              </div>
            </div>
          </div>
        </div>
        <button class="btn btn-secondary" id="next" style="min-width:5vw">Next</button>
      </div>  
      <div class="container h-100 mt-4">
        <div class="d-flex justify-content-center align-items-center">
          <a class="btn btn-primary" data-bs-toggle="collapse" href="#collapseCardForm" role="button" aria-expanded="false" aria-controls="collapseCardForm">
          Cards
          </a>
          <a class="btn btn-primary" data-bs-toggle="collapse" href="#collapseDeckForm" role="button" aria-expanded="false" aria-controls="collapseDeckForm">
          Decks
          </a>
        </div>
        <hr>
        <div class="collapse multicollapse" id="collapseCardForm">
          <div class="row h-100 justify-content-center align-items-center text-center">
            <form class="col-12" id="form-add-card">
              <span>Add Card</span>
              <div class="form-group">
                <label for="cardTermInput">Term</label>
                <input type="text" class="form-control" id="cardTermInput" placeholder="Example term">
              </div>
              <div class="form-group">
                <label for="cardDefInput">Definition</label>
                <input type="text" class="form-control" id="cardDefInput" placeholder="Example definition">
              </div>
              <select id="select-deck" aria-label="Select deck"></select>
              <div class="d-flex justify-content-center align-items-center mb-2">
                <button class="btn btn-secondary mt-2" id="add-card">Add card</button>
              </div>
            </form>   
          </div>
        </div>
        <div class="collapse multicollapse" id="collapseDeckForm">
          <div class="row h-100 justify-content-center align-items-center">
            <form class="col-12" id="form-add-deck">
              <span>Add Deck</span>
              <div class="form-group">
                <label for="deckTitleInput">Title</label>
                <input type="text" class="form-control" id="deckTitleInput" placeholder="Example title">
              </div>
              <div class="d-flex justify-content-center align-items-center mb-2">
                <button class="btn btn-secondary" id="add-deck">Add deck</button>
              </div>
            </form>   
          </div>
        </div>
      </div>
    </div>
  </main>
<?php require './incl/footer.php' ?>