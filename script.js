// Import Firebase modules
import { initializeApp } from 'https://www.gstatic.com/firebasejs/9.6.1/firebase-app.js';
import { getFirestore, collection, doc, setDoc, serverTimestamp, getDocs } from 'https://www.gstatic.com/firebasejs/9.6.1/firebase-firestore.js';

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

// Zoom variables
let zoom = 1;
let offsetX = 0;
let offsetY = 0;
const zoomLevels = [1, 1.5, 2, 3, 4, 5];
let zoomIndex = 0;

// Multi-square selection variables
let isSelecting = false;
let selectionStartX, selectionStartY, selectionEndX, selectionEndY;
let selectedSquares = [];
let isMultiMode = false;

// Store owned squares
let ownedSquares = new Map();

// Fetch owned squares from Firebase
async function loadOwnedSquares() {
    const querySnapshot = await getDocs(collection(db, 'squares'));
    querySnapshot.forEach((doc) => {
        const [x, y] = doc.id.split('-').map(Number);
        ownedSquares.set(`${x}-${y}`, doc.data());
    });
    drawGrid();
}

loadOwnedSquares();

// Draw grid with owned squares and selection
function drawGrid() {
    gridCtx.clearRect(0, 0, gridCanvas.width, gridCanvas.height);

    // Calculate boundaries to keep the grid within the viewport
    const scaledWidth = gridCanvas.width * zoom;
    const scaledHeight = gridCanvas.height * zoom;
    offsetX = Math.min(0, Math.max(offsetX, gridCanvas.width - scaledWidth));
    offsetY = Math.min(0, Math.max(offsetY, gridCanvas.height - scaledHeight));

    gridCtx.save();
    gridCtx.translate(offsetX, offsetY);
    gridCtx.scale(zoom, zoom);

    gridCtx.strokeStyle = '#00ff00';
    gridCtx.lineWidth = 0.5 / zoom;
    for (let x = 0; x < gridSize; x++) {
        for (let y = 0; y < gridSize; y++) {
            if (ownedSquares.has(`${x}-${y}`)) {
                gridCtx.fillStyle = 'rgba(255, 0, 0, 0.5)';
                gridCtx.fillRect(x * squareSize, y * squareSize, squareSize, squareSize);
            }
            gridCtx.strokeRect(x * squareSize, y * squareSize, squareSize, squareSize);
        }
    }

    // Draw selection preview
    if (isSelecting) {
        const minX = Math.min(selectionStartX, selectionEndX);
        const minY = Math.min(selectionStartY, selectionEndY);
        const maxX = Math.max(selectionStartX, selectionEndX);
        const maxY = Math.max(selectionStartY, selectionEndY);

        gridCtx.fillStyle = 'rgba(255, 255, 0, 0.3)';
        gridCtx.fillRect(minX * squareSize, minY * squareSize, (maxX - minX + 1) * squareSize, (maxY - minY + 1) * squareSize);
        gridCtx.strokeStyle = '#ffff00';
        gridCtx.lineWidth = 1 / zoom;
        gridCtx.strokeRect(minX * squareSize, minY * squareSize, (maxX - minX + 1) * squareSize, (maxY - minY + 1) * squareSize);
    }

    gridCtx.restore();
}

drawGrid();

// Toggle between single and multi-square modes
const toggleModeInput = document.getElementById('toggleMode');
toggleModeInput.addEventListener('change', () => {
    isMultiMode = toggleModeInput.checked;
});

// Hover effect with dimmed background
gridCanvas.addEventListener('mousemove', (event) => {
    gridCtx.clearRect(0, 0, gridCanvas.width, gridCanvas.height);
    drawGrid();

    if (isSelecting) return;

    const rect = gridCanvas.getBoundingClientRect();
    const mouseX = (event.clientX - rect.left - offsetX) / zoom;
    const mouseY = (event.clientY - rect.top - offsetY) / zoom;

    const gridX = Math.floor(mouseX / squareSize);
    const gridY = Math.floor(mouseY / squareSize);

    if (gridX >= 0 && gridX < gridSize && gridY >= 0 && gridY < gridSize) {
        const magSize = 100 / zoom;
        const magX = gridX * squareSize - (magSize - squareSize) / 2;
        const magY = gridY * squareSize - (magSize - squareSize) / 2;

        gridCtx.save();
        gridCtx.translate(offsetX, offsetY);
        gridCtx.scale(zoom, zoom);

        // Dimmed background for the magnified square
        gridCtx.fillStyle = 'rgba(0, 0, 0, 0.7)';
        gridCtx.fillRect(magX, magY, magSize, magSize);

        // Magnified square
        gridCtx.fillStyle = 'rgba(0, 255, 0, 0.2)';
        gridCtx.fillRect(magX, magY, magSize, magSize);
        gridCtx.strokeStyle = '#00ff00';
        gridCtx.lineWidth = 2 / zoom;
        gridCtx.strokeRect(magX, magY, magSize, magSize);
        gridCtx.shadowColor = '#00ff00';
        gridCtx.shadowBlur = 10 / zoom;

        // Placeholder image (white circle)
        gridCtx.fillStyle = '#ffffff';
        gridCtx.beginPath();
        gridCtx.arc(magX + magSize / 2, magY + magSize / 3, magSize / 6, 0, Math.PI * 2);
        gridCtx.fill();

        // Placeholder text
        gridCtx.font = `${12 / zoom}px Courier New`;
        gridCtx.fillStyle = '#00ff00';
        gridCtx.textAlign = 'center';
        gridCtx.fillText('Brand Name', magX + magSize / 2, magY + 2 * magSize / 3);

        gridCtx.restore();
    }
});

// Zoom controls
const zoomInButton = document.getElementById('zoomIn');
const zoomOutButton = document.getElementById('zoomOut');

zoomInButton.addEventListener('click', () => {
    if (zoomIndex < zoomLevels.length - 1) {
        zoomIndex++;
        zoom = zoomLevels[zoomIndex];
        drawGrid();
    }
});

zoomOutButton.addEventListener('click', () => {
    if (zoomIndex > 0) {
        zoomIndex--;
        zoom = zoomLevels[zoomIndex];
        drawGrid();
    }
});

// Mouse down for selection
let clickStartTime;
gridCanvas.addEventListener('mousedown', (event) => {
    clickStartTime = Date.now();
});

// Mouse move for selection
gridCanvas.addEventListener('mousemove', (event) => {
    const rect = gridCanvas.getBoundingClientRect();
    const mouseX = (event.clientX - rect.left - offsetX) / zoom;
    const mouseY = (event.clientY - rect.top - offsetY) / zoom;

    if (isSelecting) {
        selectionEndX = Math.floor(mouseX / squareSize);
        selectionEndY = Math.floor(mouseY / squareSize);
        selectionEndX = Math.max(0, Math.min(selectionEndX, gridSize - 1));
        selectionEndY = Math.max(0, Math.min(selectionEndY, gridSize - 1));
        drawGrid();
    }
});

// Mouse up for single square purchase or multi-square selection
gridCanvas.addEventListener('mouseup', async (event) => {
    const clickDuration = Date.now() - clickStartTime;
    const rect = gridCanvas.getBoundingClientRect();
    const mouseX = (event.clientX - rect.left - offsetX) / zoom;
    const mouseY = (event.clientY - rect.top - offsetY) / zoom;

    if (isMultiMode || clickDuration >= 1000) {
        // Start multi-square selection
        if (!isSelecting) {
            isSelecting = true;
            selectionStartX = Math.floor(mouseX / squareSize);
            selectionStartY = Math.floor(mouseY / squareSize);
            selectionEndX = selectionStartX;
            selectionEndY = selectionStartY;
        } else {
            isSelecting = false;

            const minX = Math.min(selectionStartX, selectionEndX);
            const minY = Math.min(selectionStartY, selectionEndY);
            const maxX = Math.max(selectionStartX, selectionEndX);
            const maxY = Math.max(selectionStartY, selectionEndY);

            selectedSquares = [];
            let hasOwnedSquare = false;

            for (let x = minX; x <= maxX; x++) {
                for (let y = minY; y <= maxY; y++) {
                    if (ownedSquares.has(`${x}-${y}`)) {
                        hasOwnedSquare = true;
                        break;
                    }
                    selectedSquares.push({ x, y });
                }
            }

            if (hasOwnedSquare) {
                alert('Selection contains already owned squares! Please select a different area.');
                drawGrid();
                return;
            }

            const totalPrice = selectedSquares.length * 100;
            const confirmPurchase = confirm(`You selected ${selectedSquares.length} squares. Total price: $${totalPrice}. Confirm purchase?`);

            if (confirmPurchase) {
                try {
                    for (const square of selectedSquares) {
                        await setDoc(doc(collection(db, 'squares'), `${square.x}-${square.y}`), {
                            owner: 'Test User',
                            brandName: 'Test Brand',
                            imageUrl: '',
                            price: 100,
                            timestamp: serverTimestamp()
                        });
                        ownedSquares.set(`${square.x}-${square.y}`, { owner: 'Test User', brandName: 'Test Brand', price: 100 });
                    }
                    alert(`Purchased ${selectedSquares.length} squares for $${totalPrice}!`);
                    drawGrid();
                } catch (error) {
                    console.error('Error buying squares: ', error);
                }
            } else {
                drawGrid();
            }
        }
    } else {
        // Single square purchase
        const gridX = Math.floor(mouseX / squareSize);
        const gridY = Math.floor(mouseY / squareSize);

        if (gridX >= 0 && gridX < gridSize && gridY >= 0 && gridY < gridSize) {
            if (ownedSquares.has(`${gridX}-${gridY}`)) {
                alert('This square is already owned!');
                return;
            }

            try {
                await setDoc(doc(collection(db, 'squares'), `${gridX}-${gridY}`), {
                    owner: 'Test User',
                    brandName: 'Test Brand',
                    imageUrl: '',
                    price: 100,
                    timestamp: serverTimestamp()
                });
                ownedSquares.set(`${gridX}-${gridY}`, { owner: 'Test User', brandName: 'Test Brand', price: 100 });
                alert(`Square (${gridX}, ${gridY}) bought by Test User for $100!`);
                drawGrid();
            } catch (error) {
                console.error('Error buying square: ', error);
            }
        }
    }
});

gridCanvas.addEventListener('mouseout', () => {
    isSelecting = false;
    gridCtx.clearRect(0, 0, gridCanvas.width, gridCanvas.height);
    drawGrid();
});

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