let questions = [];
let shuffledQuestions = [];
let currentIndex = 0;
let timer;
let timeLeft = 3600;
let answers = [];
let score = 0;
let userId = "";
const isFileProtocol = window.location.protocol === "file:";

function ensureHttpContext() {
    if (isFileProtocol) {
        alert("Open via http://localhost/crypto-puzzle-main/index.html (not file://).");
        return false;
    }
    return true;
}

// Fetch questions from the database
async function fetchQuestions() {
    if (!ensureHttpContext()) return;
    try {
        const response = await fetch("quiz.php");
        const data = await response.json();

        if (!data.questions || !Array.isArray(data.questions)) {
            throw new Error("Invalid data format received from server.");
        }

        questions = data.questions;
        shuffledQuestions = questions.sort(() => Math.random() - 0.5);

        // Save questions in sessionStorage
        sessionStorage.setItem("questions", JSON.stringify(questions));
        sessionStorage.setItem("shuffledQuestions", JSON.stringify(shuffledQuestions));

        showQuestion();
    } catch (error) {
        console.error("Error fetching questions:", error);
    }
}



// Save score using a separate function to fix the error
async function saveScoreToServer() {
    if (!ensureHttpContext()) return;
    try {
        const response = await fetch("save_score.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `username=${encodeURIComponent(userId)}&score=${score}`
        });
        const result = await response.text();
        console.log("Score saved to server:", result);
    } catch (error) {
        console.error("Error saving score to server:", error);
    }
}

async function submitAnswers() {
    if (!ensureHttpContext()) return;
    saveAnswer();  // Save current answer

    // Calculate score
    score = 0;
    for (let i = 0; i < questions.length; i++) {
        if (answers[i] && answers[i].toUpperCase() === questions[i].answer.toUpperCase()) {
            switch (questions[i].difficulty.toLowerCase()) {
                case 'easy': score += 10; break;
                case 'medium': score += 20; break;
                case 'hard': score += 30; break;
                default: score += 5;
            }
        }
    }

    // Update score
    document.getElementById('score').innerText = "Score: " + score;

    const username = sessionStorage.getItem("username"); // ✅ Fetch from sessionStorage

    if (!username) {
        showPopup("Username is missing! Please log in again.", "error");
        return;
    }

    // Save score to server
    const response = await fetch("save_score.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `username=${encodeURIComponent(username)}&score=${encodeURIComponent(score)}`
    });

    const result = await response.text();

    sessionStorage.removeItem("scoreSubmitted");

    showPopup("Quiz submitted! Your score has been saved.", "success");
}

// Start the quiz
function startQuiz() {
    document.getElementById('login-container').style.display = 'none';
    document.getElementById('quiz-container').style.display = 'block';

    // Check if data exists in sessionStorage
    const storedQuestions = sessionStorage.getItem("questions");
    const storedIndex = sessionStorage.getItem("currentIndex");
    const storedTimeLeft = sessionStorage.getItem("timeLeft");
    const storedScore = sessionStorage.getItem("score");
    const storedAnswers = sessionStorage.getItem("answers");

    if (storedQuestions && storedIndex !== null && storedTimeLeft !== null) {
        questions = JSON.parse(storedQuestions);
        shuffledQuestions = [...questions]; // Keep the same order
        currentIndex = parseInt(storedIndex, 10);
        timeLeft = parseInt(storedTimeLeft, 10);
        score = parseInt(storedScore, 10) || 0;
        answers = storedAnswers ? JSON.parse(storedAnswers) : [];

        showQuestion();
        startTimer();
    } else {
        fetchQuestions();
        startTimer();
    }
}


// Show current question
function showQuestion() {
    let currentQuestion = shuffledQuestions[currentIndex];

    document.getElementById('question').innerText = currentQuestion.question;
    let difficultyLabel = document.getElementById('difficulty-label');
    difficultyLabel.innerText = currentQuestion.difficulty;
    difficultyLabel.className = currentQuestion.difficulty.toLowerCase();
    document.getElementById('answer').value = answers[currentIndex] || "";

    // Update progress
    document.getElementById('progress').innerText = `Question: ${currentIndex + 1} / ${shuffledQuestions.length}`;

    // Store progress
    sessionStorage.setItem("currentIndex", currentIndex);
}



// Navigate to the next question
function nextQuestion() {
    saveAnswer();
    if (currentIndex < shuffledQuestions.length - 1) {
        currentIndex++;
        sessionStorage.setItem("currentIndex", currentIndex); // Save progress
        showQuestion();
    } else {
        alert("Questions are completed");
    }
}




// Save the current answer
function saveAnswer() {
    answers[currentIndex] = document.getElementById('answer').value.trim();
    sessionStorage.setItem("answers", JSON.stringify(answers)); // Save answers
}


// Start countdown timer
function startTimer() {
    if (sessionStorage.getItem("timeLeft")) {
        timeLeft = parseInt(sessionStorage.getItem("timeLeft"), 10);
    }

    timer = setInterval(() => {
        if (timeLeft > 0) {
            timeLeft--;
            sessionStorage.setItem("timeLeft", timeLeft); // Store timer

            const hours = Math.floor(timeLeft / 3600);
            const minutes = Math.floor((timeLeft % 3600) / 60);
            const seconds = timeLeft % 60;

            document.getElementById('time').innerText = `Time Left: ${hours < 10 ? '0' : ''}${hours}:${minutes < 10 ? '0' : ''}${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
        } else {
            clearInterval(timer);
            alert("Time's up! Answers will be submitted automatically.");
            submitAnswers();
        }
    }, 1000);
}



async function login() {
    if (!ensureHttpContext()) return;
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');

    // Trigger built-in browser validation
    if (!usernameInput.checkValidity()) {
        usernameInput.reportValidity();
        return;
    }
    if (!passwordInput.checkValidity()) {
        passwordInput.reportValidity();
        return;
    }

    const username = usernameInput.value.trim();
    const password = passwordInput.value.trim();

    const response = await fetch("login.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
    });

    try {
        const result = await response.json();

        if (result.status === "success") {
            sessionStorage.setItem("username", username);
            userId = username;
            startQuiz();
        } else {
            alert(result.message || "Login failed");
        }
    } catch (e) {
        console.error("Error parsing login response:", e);
        // Fallback: If JSON fails, check text for "success" just in case of weird server output
        const text = await response.clone().text();
        console.log("Raw response:", text);
        alert("An error occurred during login. Check console for details.");
    }
}




async function logout() {
    if (!ensureHttpContext()) return;
    const confirmLogout = confirm("Are you sure you want to logout?");
    if (confirmLogout) {
        await fetch("logout.php", { method: "POST" });
        sessionStorage.clear();  // ✅ Clear saved data
        location.reload();
    }
}

// Show popup with slide-in animation
let popupTimeout;  // To manage auto-close timeout

// Show popup with slide-in animation, emoji, and color
function showPopup(message, type = 'info') {
    const popup = document.getElementById('popup');
    const popupMessage = document.getElementById('popup-message');
    const popupEmoji = document.getElementById('popup-emoji');

    // Set emoji and color based on type
    switch (type) {
        case 'success':
            popupEmoji.innerText = '✅';
            popup.style.backgroundColor = '#d4edda';  // Light green for success
            popupMessage.style.color = '#155724';      // Dark green text
            break;
        case 'error':
            popupEmoji.innerText = '❌';
            popup.style.backgroundColor = '#f8d7da';  // Light red for error
            popupMessage.style.color = '#721c24';      // Dark red text
            break;
        case 'warning':
            popupEmoji.innerText = '⚠️';
            popup.style.backgroundColor = '#fff3cd';  // Light yellow for warning
            popupMessage.style.color = '#856404';      // Dark yellow text
            break;
        default:
            popupEmoji.innerText = '🔔';
            popup.style.backgroundColor = '#d1ecf1';  // Light blue for info
            popupMessage.style.color = '#0c5460';      // Dark blue text
    }

    popupMessage.innerText = message;
    popup.style.display = 'block';

    // Slide-in effect
    setTimeout(() => {
        popup.style.bottom = '20px';
        popup.style.opacity = '1';
    }, 10);  // Small delay to trigger transition

    // Auto-close popup after 3 seconds
    clearTimeout(popupTimeout);  // Clear previous timeout if exists
    popupTimeout = setTimeout(() => {
        closePopup();
    }, 3000);  // 3 seconds before auto-close
}

// Close popup with slide-out animation
function closePopup() {
    const popup = document.getElementById('popup');
    popup.style.bottom = '-100px';  // Slide down out of view
    popup.style.opacity = '0';       // Fade out
    setTimeout(() => {
        popup.style.display = 'none';
    }, 500);  // Wait for slide-out animation to finish
}

window.onload = function () {
    const storedUser = sessionStorage.getItem("username");

    if (storedUser) {
        userId = storedUser;
        document.getElementById('username').value = storedUser; // Ensure the username field is populated

        document.getElementById('login-container').style.display = 'none';
        document.getElementById('quiz-container').style.display = 'block';

        startQuiz();
    } else {
        document.getElementById('login-container').style.display = 'block';
        document.getElementById('quiz-container').style.display = 'none';
    }
};





