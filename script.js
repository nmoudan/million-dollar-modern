// Import Firebase modules
import { initializeApp } from 'https://www.gstatic.com/firebasejs/9.6.1/firebase-app.js';
import { getFirestore, collection, doc, setDoc, serverTimestamp } from 'https://www.gstatic.com/firebasejs/9.6.1/firebase-firestore.js';

// Firebase Initialization
const firebaseConfig = {
    apiKey: "AIzaSyD7kAuxbDJzHHhF-5UFPUvD8ACuLRE",
    authDomain: "milliondollarmodern.firebaseapp.com",
    projectId: "milliondollarmodern",
    storageBucket: "milliondollarmodern.appspot.com",
    messagingSenderId: "1384933959990",
    appId: "1:1384933959990:web:a7db1af24bff77603617",
    measurementId: "G-KSZ7HTT6Y4"
};
const app = initializeApp(firebaseConfig);
const db = getFirestore(app);

// Grid Canvas
const gridCanvas = document.getElementById('gridCanvas');
const gridCtx = gridCanvas.getContext('2d');

gridCanvas.width = 500;
gridCanvas.height = 500;
const gridSize = 100;
const squareSize = gridCanvas.width / gridSize;

// Draw initial grid
gridCtx.strokeStyle = '#00ff00';
gridCtx.lineWidth = 0.5;
for (let x = 0; x < gridSize; x++) {
    for (let y = 0; y < gridSize; y++) {
        gridCtx.strokeRect(x * squareSize, y * squareSize, squareSize, squareSize);
    }
}

// Hover effect
gridCanvas.addEventListener('mousemove', (event) => {
    gridCtx.clearRect(0, 0, gridCanvas.width, gridCanvas.height);
    redrawGrid();

    const rect = gridCanvas.getBoundingClientRect();
    const mouseX = event.clientX - rect.left;
    const mouseY = event.clientY - rect.top;

    const gridX = Math.floor(mouseX / squareSize);
    const gridY = Math.floor(mouseY / squareSize);

    const magSize = 100;
    const magX = gridX * squareSize - (magSize - squareSize) / 2;
    const magY = gridY * squareSize - (magSize - squareSize) / 2;

    gridCtx.fillStyle = 'rgba(0, 255, 0, 0.2)';
    gridCtx.fillRect(magX, magY, magSize, magSize);
    gridCtx.strokeStyle = '#00ff00';
    gridCtx.lineWidth = 2;
    gridCtx.strokeRect(magX, magY, magSize, magSize);
    gridCtx.shadowColor = '#00ff00';
    gridCtx.shadowBlur = 10;

    gridCtx.fillStyle = '#ffffff';
    gridCtx.beginPath();
    gridCtx.arc(magX + magSize / 2, magY + magSize / 3, magSize / 6, 0, Math.PI * 2);
    gridCtx.fill();

    gridCtx.font = '12px Courier New';
    gridCtx.fillStyle = '#00ff00';
    gridCtx.textAlign = 'center';
    gridCtx.fillText('Brand Name', magX + magSize / 2, magY + 2 * magSize / 3);
});

// Click to "buy" a square
gridCanvas.addEventListener('click', async (event) => {
    const rect = gridCanvas.getBoundingClientRect();
    const mouseX = event.clientX - rect.left;
    const mouseY = event.clientY - rect.top;

    const gridX = Math.floor(mouseX / squareSize);
    const gridY = Math.floor(mouseY / squareSize);

    // Save to Firebase
    try {
        await setDoc(doc(collection(db, 'squares'), `${gridX}-${gridY}`), {
            owner: 'Test User',
            brandName: 'Test Brand',
            imageUrl: '',
            timestamp: serverTimestamp()
        });
        alert(`Square (${gridX}, ${gridY}) bought by Test User!`);
    } catch (error) {
        console.error('Error buying square: ', error);
    }
});

gridCanvas.addEventListener('mouseout', () => {
    gridCtx.clearRect(0, 0, gridCanvas.width, gridCanvas.height);
    redrawGrid();
});

function redrawGrid() {
    gridCtx.strokeStyle = '#00ff00';
    gridCtx.lineWidth = 0.5;
    gridCtx.shadowBlur = 0;
    for (let x = 0; x < gridSize; x++) {
        for (let y = 0; y < gridSize; y++) {
            gridCtx.strokeRect(x * squareSize, y * squareSize, squareSize, squareSize);
        }
    }
}

// Matrix Animation Canvas
const matrixCanvas = document.getElementById('matrixCanvas');
const matrixCtx = matrixCanvas.getContext('2d');

matrixCanvas.width = window.innerWidth;
matrixCanvas.height = 150;

const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%^&*()_+-=[]{}|;:,.<>?';
const fontSize = 14;
const columns = matrixCanvas.width / fontSize;
const drops = [];

for (let x = 0; x < columns; x++) {
    drops[x] = 1;
}

function drawMatrix() {
    matrixCtx.fillStyle = 'rgba(0, 0, 0, 0.05)';
    matrixCtx.fillRect(0, 0, matrixCanvas.width, matrixCanvas.height);

    matrixCtx.fillStyle = '#00ff00';
    matrixCtx.font = fontSize + 'px Courier New';

    for (let i = 0; i < drops.length; i++) {
        const text = chars.charAt(Math.floor(Math.random() * chars.length));
        matrixCtx.fillText(text, i * fontSize, drops[i] * fontSize);

        if (drops[i] * fontSize > matrixCanvas.height && Math.random() > 0.975) {
            drops[i] = 0;
        }
        drops[i]++;
    }
}

setInterval(drawMatrix, 50);