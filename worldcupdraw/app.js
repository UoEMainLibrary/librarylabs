const PEOPLE = [
  "Scott Renton",
  "Gavin Willshaw",
  "Ianthe Sutherland",
  "Simon Bowie",
  "Greig Christie",
  "Gordon Andrew",
  "Laura MacNeil",
  "Daryl Green",
  "Stuart Robinson",
  "Jeremy Upton",
  "Lesley Bryson",
  "Caroline Milligan",
  "Cathlin MacAulay",
  "Kirsty Stewart",
  "Simeon Newbatt",
  "Alasdair MacDonald",
  "Theo Andrew",
  "Malcolm MacCallum",
  "Ross McGregor",
  "Rachel Hosker",
  "Hannah Mateer",
  "Scott Docking",
  "Angela Laurins",
  "Fiona Wright",
].sort((left, right) => left.localeCompare(right));

const POTS_1_AND_2 = [
  { name: "Canada", flag: "🇨🇦" },
  { name: "Mexico", flag: "🇲🇽" },
  { name: "United States", flag: "🇺🇸" },
  { name: "Spain", flag: "🇪🇸" },
  { name: "Argentina", flag: "🇦🇷" },
  { name: "France", flag: "🇫🇷" },
  { name: "England", flagSvg: createFlagSvg("white", [
    '<rect width="60" height="36" fill="#ffffff"/>',
    '<rect x="24" width="12" height="36" fill="#ce1126"/>',
    '<rect y="12" width="60" height="12" fill="#ce1126"/>',
  ]) },
  { name: "Brazil", flag: "🇧🇷" },
  { name: "Portugal", flag: "🇵🇹" },
  { name: "Netherlands", flag: "🇳🇱" },
  { name: "Belgium", flag: "🇧🇪" },
  { name: "Germany", flag: "🇩🇪" },
  { name: "Croatia", flag: "🇭🇷" },
  { name: "Morocco", flag: "🇲🇦" },
  { name: "Colombia", flag: "🇨🇴" },
  { name: "Uruguay", flag: "🇺🇾" },
  { name: "Switzerland", flag: "🇨🇭" },
  { name: "Japan", flag: "🇯🇵" },
  { name: "Senegal", flag: "🇸🇳" },
  { name: "IR Iran", flag: "🇮🇷" },
  { name: "Korea Republic", flag: "🇰🇷" },
  { name: "Ecuador", flag: "🇪🇨" },
  { name: "Austria", flag: "🇦🇹" },
  { name: "Australia", flag: "🇦🇺" },
];

const POTS_3_AND_4 = [
  { name: "Norway", flag: "🇳🇴" },
  { name: "Panama", flag: "🇵🇦" },
  { name: "Egypt", flag: "🇪🇬" },
  { name: "Algeria", flag: "🇩🇿" },
  { name: "Scotland", flagSvg: createFlagSvg("#0065bd", [
    '<rect width="60" height="36" fill="#0065bd"/>',
    '<path d="M0 0 10 0 60 28 60 36 50 36 0 8Z" fill="#ffffff"/>',
    '<path d="M60 0 50 0 0 28 0 36 10 36 60 8Z" fill="#ffffff"/>',
  ]) },
  { name: "Paraguay", flag: "🇵🇾" },
  { name: "Tunisia", flag: "🇹🇳" },
  { name: "Côte d'Ivoire", flag: "🇨🇮" },
  { name: "Uzbekistan", flag: "🇺🇿" },
  { name: "Qatar", flag: "🇶🇦" },
  { name: "Saudi Arabia", flag: "🇸🇦" },
  { name: "South Africa", flag: "🇿🇦" },
  { name: "Jordan", flag: "🇯🇴" },
  { name: "Cabo Verde", flag: "🇨🇻" },
  { name: "Ghana", flag: "🇬🇭" },
  { name: "Curaçao", flag: "🇨🇼" },
  { name: "Haiti", flag: "🇭🇹" },
  { name: "New Zealand", flag: "🇳🇿" },
  { name: "Bosnia and Herzegovina", flag: "🇧🇦" },
  { name: "Czechia", flag: "🇨🇿" },
  { name: "Sweden", flag: "🇸🇪" },
  { name: "Türkiye", flag: "🇹🇷" },
  { name: "Congo DR", flag: "🇨🇩" },
  { name: "Iraq", flag: "🇮🇶" },
];

const STORAGE_KEY = "worldcupdraw.assignments.v2";
const DRAW_STATE_KEY = "worldcupdraw.state.v2";

const peopleGrid = document.getElementById("people-grid");
const drawButton = document.getElementById("draw-button");
const nextButton = document.getElementById("next-button");
const copyButton = document.getElementById("copy-button");
const statusNode = document.getElementById("status");
const cardTemplate = document.getElementById("person-card-template");
const themeAudio = document.getElementById("theme-audio");

let cardNodes = [];

function createFlagSvg(background, shapes) {
  const svg = `
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 36">
      <rect width="60" height="36" fill="${background}"/>
      ${shapes.join("")}
    </svg>
  `;
  return `data:image/svg+xml;charset=UTF-8,${encodeURIComponent(svg)}`;
}

function startThemeAudio() {
  if (!themeAudio) {
    return;
  }

  themeAudio.play().catch(() => {
    const resumeAudio = () => {
      themeAudio.play().catch(() => {});
    };

    window.addEventListener("pointerdown", resumeAudio, { once: true });
    window.addEventListener("keydown", resumeAudio, { once: true });
  });
}

function createBoard() {
  const fragment = document.createDocumentFragment();

  PEOPLE.forEach((person) => {
    const card = cardTemplate.content.firstElementChild.cloneNode(true);
    card.querySelector(".person-name").textContent = person;
    fragment.appendChild(card);
  });

  peopleGrid.appendChild(fragment);
  cardNodes = Array.from(document.querySelectorAll(".person-card"));
}

function shuffle(array) {
  const shuffled = [...array];
  for (let index = shuffled.length - 1; index > 0; index -= 1) {
    const swapIndex = Math.floor(Math.random() * (index + 1));
    [shuffled[index], shuffled[swapIndex]] = [shuffled[swapIndex], shuffled[index]];
  }
  return shuffled;
}

function saveAssignments(assignments) {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(assignments));
}

function loadAssignments() {
  const raw = localStorage.getItem(STORAGE_KEY);
  if (!raw) {
    return null;
  }

  try {
    return JSON.parse(raw);
  } catch (error) {
    localStorage.removeItem(STORAGE_KEY);
    return null;
  }
}

function saveDrawState(state) {
  localStorage.setItem(DRAW_STATE_KEY, JSON.stringify(state));
}

function loadDrawState() {
  const raw = localStorage.getItem(DRAW_STATE_KEY);
  if (!raw) {
    return null;
  }

  try {
    return JSON.parse(raw);
  } catch (error) {
    localStorage.removeItem(DRAW_STATE_KEY);
    return null;
  }
}

function emptySlots() {
  cardNodes.forEach((card) => {
    card.classList.remove("is-revealed");
    card.querySelectorAll(".team-pill").forEach((pill) => {
      pill.classList.remove("is-filled");
      pill.classList.add("is-empty");
      pill.querySelector(".team-flag").textContent = "?";
      pill.querySelector(".team-name").textContent = "Waiting for draw";
    });
  });
}

function renderFlag(flagNode, team) {
  flagNode.innerHTML = "";

  if (team.flagSvg) {
    const image = document.createElement("img");
    image.src = team.flagSvg;
    image.alt = `${team.name} flag`;
    image.className = "flag-image";
    flagNode.appendChild(image);
    return;
  }

  flagNode.textContent = team.flag;
}

function renderAssignment(personIndex, slotIndex, team) {
  const card = cardNodes[personIndex];
  const slot = card.querySelector(`.team-slot[data-slot="${slotIndex}"] .team-pill`);
  slot.classList.remove("is-empty");
  slot.classList.add("is-filled");
  renderFlag(slot.querySelector(".team-flag"), team);
  slot.querySelector(".team-name").textContent = team.name;
  card.classList.add("is-revealed");
}

function renderAssignments(assignments) {
  emptySlots();
  assignments.forEach((entry, index) => {
    entry.teams.forEach((team, slotIndex) => {
      if (team) {
        renderAssignment(index, slotIndex, team);
      }
    });
  });
}

function buildSeededAssignments() {
  const upperPotTeams = shuffle(POTS_1_AND_2);
  const lowerPotTeams = shuffle(POTS_3_AND_4);
  return PEOPLE.map((person) => ({
    person,
    teams: [],
  })).map((entry, index) => ({
    ...entry,
    pendingTeams: [lowerPotTeams[index], upperPotTeams[index]],
  }));
}

function revealNextTeam() {
  const state = loadDrawState();
  if (!state || !Array.isArray(state.assignments)) {
    statusNode.textContent = "Start a new draw first.";
    return;
  }

  if (state.step >= PEOPLE.length * 2) {
    statusNode.textContent = "Draw complete. Everyone has two countries.";
    nextButton.disabled = true;
    return;
  }

  const roundIndex = state.step < PEOPLE.length ? 0 : 1;
  const personIndex = roundIndex === 0 ? state.step : state.step - PEOPLE.length;
  const entry = state.assignments[personIndex];
  const team = entry.pendingTeams[roundIndex];
  entry.teams[roundIndex] = team;

  renderAssignment(personIndex, roundIndex, team);
  state.step += 1;
  saveAssignments(state.assignments);
  saveDrawState(state);

  const roundLabel = roundIndex + 1;
  const potLabel = roundIndex === 0 ? "Pots 3-4" : "Pots 1-2";
  statusNode.textContent = `${potLabel}: ${entry.person} draws ${team.name}.`;

  if (state.step === PEOPLE.length) {
    statusNode.textContent = "Pots 3-4 complete. Press again to start the Pots 1-2 draw.";
  }

  if (state.step >= PEOPLE.length * 2) {
    statusNode.textContent = "Draw complete. Everyone has two countries.";
    nextButton.disabled = true;
  }
}

function startNewDraw() {
  const assignments = buildSeededAssignments();
  saveAssignments(assignments);
  saveDrawState({ assignments, step: 0 });
  renderAssignments(assignments);
  nextButton.disabled = false;
  statusNode.textContent = "Fresh draw loaded. Press Reveal Next Team for the first Pots 3-4 country.";
}

function normaliseAssignments(assignments) {
  return assignments.map((entry, index) => {
    const fallbackPending = entry.pendingTeams || [null, null];
    return {
      person: entry.person || PEOPLE[index],
      teams: Array.isArray(entry.teams) ? entry.teams : [],
      pendingTeams: fallbackPending,
    };
  });
}

function hydrateFromStorage() {
  const savedState = loadDrawState();
  const savedAssignments = loadAssignments();

  if (savedState && Array.isArray(savedState.assignments)) {
    const assignments = normaliseAssignments(savedState.assignments);
    renderAssignments(assignments);
    nextButton.disabled = savedState.step >= PEOPLE.length * 2;
    statusNode.textContent =
      savedState.step >= PEOPLE.length * 2
        ? "Loaded the completed draw."
        : "Loaded the latest draw in progress.";
    saveDrawState({ assignments, step: savedState.step || 0 });
    saveAssignments(assignments);
    return;
  }

  if (savedAssignments && savedAssignments.length === PEOPLE.length) {
    const assignments = normaliseAssignments(savedAssignments);
    renderAssignments(assignments);
    nextButton.disabled = true;
    statusNode.textContent = "Loaded the latest completed draw.";
    saveDrawState({ assignments, step: PEOPLE.length * 2 });
  }
}

async function copyResults() {
  const assignments = loadAssignments();
  if (!assignments) {
    statusNode.textContent = "Start the draw first, then I can copy it.";
    return;
  }

  const lines = assignments.map((entry) => {
    const visibleTeams = entry.teams.filter(Boolean);
    const resultText = visibleTeams.length
      ? visibleTeams.map((team) => team.name).join(", ")
      : "No countries drawn yet";
    return `${entry.person}: ${resultText}`;
  });

  try {
    await navigator.clipboard.writeText(lines.join("\n"));
    statusNode.textContent = "Results copied to clipboard.";
  } catch (error) {
    statusNode.textContent = "Clipboard copy failed in this browser, but the draw is still on screen.";
  }
}

function init() {
  createBoard();
  hydrateFromStorage();
  startThemeAudio();
}

drawButton.addEventListener("click", startNewDraw);
nextButton.addEventListener("click", revealNextTeam);
copyButton.addEventListener("click", copyResults);

init();
