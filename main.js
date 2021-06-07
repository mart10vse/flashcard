const createCard = ({ term, definition, deck }) => ({
  term,
  definition,
  deck,
  setTerm(term) {
    this.term = term;
  },
  setDefinition(definition) {
    this.definition = definition;
  },
  setDeck(deck) {
    this.deck = deck;
  }
});

const createDeck = ({ title, cards = [] }) => ({
  title,
  cards,
  setTitle(title) {
    this.title = title;
  },
  addCard(card) {
    cards.push(card);
  }
});

var actualCard = 0;
var decks = new Map();
var selectedDeck = '';

const cardDef = document.getElementById('card-def');
const cardTerm = document.getElementById('card-term');
const selectDeck = document.getElementById('select-deck');
const clearDeckBtn = document.getElementById('clear').addEventListener("click", clearDeck);
const shuffleBtn = document.getElementById('shuffle').addEventListener("click", shuffleDeck );
const addDeckBtn = document.getElementById('add-deck').addEventListener("click", (e) => addDeck(e));
const addCardBtn = document.getElementById('add-card').addEventListener("click", (e) => addCardToDeck(e));
const deckList = document.getElementById('deck-list');
deckList.addEventListener("click",(e) => setSelectedDeck(e));
const prev = document.getElementById('previous').addEventListener("click", () => displayCard(-1));
const next = document.getElementById('next').addEventListener("click", () => displayCard(1));
const saveDecksBtn = document.getElementById('save-decks').addEventListener("click", saveDeck);

function displayCard(value = 0, deckTitle = selectedDeck) {
  try {
    if (value == 0)
      setDefaultCard();
    if (value == 1)
      (actualCard < decks.get(deckTitle).cards.length - 1) ? actualCard++ : actualCard = 0;
    if (value == -1)
      (actualCard > 0) ? actualCard-- : actualCard = decks.get(deckTitle).cards.length - 1;
    
    cardTerm.innerText = decks.get(deckTitle).cards[actualCard].term;
    cardDef.innerText = decks.get(deckTitle).cards[actualCard].definition;
  } catch(e) {
    if (typeof decks.get(deckTitle) == 'undefined') {
      console.log("no cards in deck");
    } else {
      console.log(e);
    }
  }
}

function addDeck(event) {
  event.preventDefault();
  let deckTitle = document.getElementById('deckTitleInput');
  decks.set(deckTitle.value, createDeck(deckTitle.value));
  updateDeckOptions();
}

function setSelectedDeck(event) {
  selectedDeck = event.target.innerText;
  displayCard();
}

function shuffleDeck() {
  if (selectDeck.value == '') {
    console.log("First select a deck...");
  } else {
    decks.get(selectedDeck).cards = decks.get(selectedDeck).cards
        .map((a) => ({sort: Math.random(), value: a}))
        .sort((a,b) => a.sort - b.sort)
        .map((a) => a.value);   
    console.log(decks.get(selectedDeck).cards);
  }
}

function clearDeck() {
  if (selectDeck.value == '') {
    console.log("First select a deck...");
  } else {
    decks.get(selectedDeck).cards.length = 0;
    setDefaultCard();
    console.log(decks.get(selectedDeck).cards);
  }
}

function addCardToDeck(event) {
  event.preventDefault();
  if (selectDeck.value == '') {
    console.log("There are no decks!");
  } else {
    let termInput = document.getElementById('cardTermInput');
    let defInput = document.getElementById('cardDefInput');
    decks.get(selectDeck.value).addCard(
      createCard({ term: termInput.value, definition: defInput.value, deck: selectDeck.value }));
    termInput.value = "";
    defInput.value = "";
  }
}

function setDefaultCard() {
  cardTerm.innerText = "Term";
  cardDef.innerText = "Definition";
}

function updateDeckOptions() {
  selectDeck.innerHTML = '';
  deckList.innerHTML = '';
  decks.forEach((value,key) => {
    let elopt = document.createElement("option");
    let elli = document.createElement("li");
    elopt.textContent = key;
    elopt.value = key;
    elli.textContent = key;
    elli.classList.add("list-group-item");
    selectDeck.appendChild(elopt);
    deckList.appendChild(elli);
  });
}

function saveDeck() {
  var jsondata;
  var data = JSON.stringify(decks.get(selectedDeck).cards);

  var xhr = new XMLHttpRequest();
  xhr.open("POST", "http://localhost/flashcards/utils/savedecks.php", !0);
  xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
  xhr.send(data);
  xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
          jsonString = JSON.stringify(xhr.responseText).replace("<br />", "");
          jsondata = JSON.parse(jsonString);
          console.log(jsondata);
      }
  }
}

setDefaultCard();