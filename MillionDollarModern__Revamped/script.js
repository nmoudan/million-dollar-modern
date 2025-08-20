// Import Firebase modules
import { initializeApp } from 'https://www.gstatic.com/firebasejs/9.6.1/firebase-app.js';
import { getFirestore, collection, doc, setDoc, serverTimestamp, getDocs, updateDoc } from 'https://www.gstatic.com/firebasejs/9.6.1/firebase-firestore.js';
import { getAuth, signInWithEmailAndPassword, signOut, onAuthStateChanged } from 'https://www.gstatic.com/firebasejs/9.6.1/firebase-auth.js';
import { getStorage, ref, uploadBytesResumable, getDownloadURL } from 'https://www.gstatic.com/firebasejs/9.6.1/firebase-storage.js';

// Firebase Initialization
const firebaseConfig = {
    apiKey: "AIzaSyDyIxauabjDZjHhHF-5dUFPvUD0AUCuLrE",
    authDomain: "milliondollarmodern.firebaseapp.com",
    projectId: "milliondollarmodern",
    storageBucket: "milliondollarmodern.firebasestorage.app",
    messagingSenderId: "138490359990",
    appId: "1:138490359990:web:a7db1afa24bff7f7603617",
    measurementId: "G-KSZ7HTT6Y4"
};
const app = initializeApp(firebaseConfig);
const db = getFirestore(app);
const auth = getAuth(app);
const storage = getStorage(app);

// Grid Canvas
const gridCanvas = document.getElementById('gridCanvas');
const gridCtx = gridCanvas.getContext('2d');

// Grid dimensions in squares
const gridWidth = 180;
const gridHeight = 90;
const squareSize = 10; // Base square size in pixels
const blockSize = 2;
const pricePerBlock = 59; // Price per 2x2 block
const resaleCommissionRate = 0.20; // 20% commission on resale

// Adjust canvas size dynamically based on container
function resizeGridCanvas() {
    const container = document.getElementById('gridContainer');
    const containerWidth = container.clientWidth;
    const containerHeight = container.clientHeight;
    gridCanvas.width = containerWidth;
    gridCanvas.height = containerHeight;
    // Adjust drawing scale based on container size
    gridCtx.scale(containerWidth / (gridWidth * squareSize), containerHeight / (gridHeight * squareSize));
}

// Create offscreen canvases
const gridLayerCanvas = document.createElement('canvas');
gridLayerCanvas.width = gridWidth * squareSize;
gridLayerCanvas.height = gridHeight * squareSize;
const gridLayerCtx = gridLayerCanvas.getContext('2d');

const baseCanvas = document.createElement('canvas');
baseCanvas.width = gridWidth * squareSize;
baseCanvas.height = gridHeight * squareSize;
const baseCtx = baseCanvas.getContext('2d');

const placeholderCanvas = document.createElement('canvas');
placeholderCanvas.width = blockSize * squareSize;
placeholderCanvas.height = blockSize * squareSize;
const placeholderCtx = placeholderCanvas.getContext('2d');

// Multi-square selection variables
let isSelecting = false;
let selectionStartX, selectionStartY, selectionEndX, selectionEndY;
let selectedSquares = [];
let isMultiMode = false;

// Image selection mode
let isImageSelectionMode = false;
let pendingImageBlob = null;
let pendingImageUrl = null;

// Resell mode
let isResellMode = false;

// Store owned squares and placeholder squares
let ownedSquares = new Map();
let placeholderSquares = new Map();
let placeholderImages = new Map();

// Loading and Auth indicators
const loadingIndicator = document.getElementById('loadingIndicator');
const loginButton = document.getElementById('loginButton');
const logoutButton = document.getElementById('logoutButton');
const userDisplay = document.getElementById('userDisplay');
const loginForm = document.getElementById('loginForm');
const emailInput = document.getElementById('emailInput');
const passwordInput = document.getElementById('passwordInput');
const submitLogin = document.getElementById('submitLogin');
const cancelLogin = document.getElementById('cancelLogin');
const uploadSection = document.getElementById('uploadSection');
const imageUpload = document.getElementById('imageUpload');
const uploadButton = document.getElementById('uploadButton');

// Debug: Check if upload elements are found
console.log('uploadSection:', uploadSection);
console.log('imageUpload:', imageUpload);
console.log('uploadButton:', uploadButton);

// Debounce function to limit redraw frequency
function debounce(func, wait) {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

// Draw static grid lines once
function drawGridLayer() {
    gridLayerCtx.strokeStyle = '#00ff00';
    gridLayerCtx.lineWidth = 0.5;
    for (let x = 0; x < gridWidth; x++) {
        for (let y = 0; y < gridHeight; y++) {
            gridLayerCtx.strokeRect(x * squareSize, y * squareSize, squareSize, squareSize);
        }
    }
}

// Pre-render placeholder block images
function preRenderPlaceholder(shape, color, symbol) {
    placeholderCtx.clearRect(0, 0, placeholderCanvas.width, placeholderCanvas.height);

    for (let x = 0; x < blockSize; x++) {
        for (let y = 0; y < blockSize; y++) {
            placeholderCtx.fillStyle = color;
            placeholderCtx.beginPath();
            if (shape === 0) {
                placeholderCtx.arc((x + 0.5) * squareSize, (y + 0.5) * squareSize, squareSize / 3, 0, Math.PI * 2);
            } else if (shape === 1) {
                placeholderCtx.moveTo((x + 0.5) * squareSize, y * squareSize + squareSize / 6);
                placeholderCtx.lineTo(x * squareSize + squareSize / 6, y * squareSize + 5 * squareSize / 6);
                placeholderCtx.lineTo(x * squareSize + 5 * squareSize / 6, y * squareSize + 5 * squareSize / 6);
                placeholderCtx.closePath();
            } else {
                placeholderCtx.rect(x * squareSize + squareSize / 4, y * squareSize + squareSize / 4, squareSize / 2, squareSize / 2);
            }
            placeholderCtx.fill();

            placeholderCtx.font = '6px Courier New';
            placeholderCtx.fillStyle = '#000000';
            placeholderCtx.textAlign = 'center';
            placeholderCtx.fillText(symbol, (x + 0.5) * squareSize, (y + 0.5) * squareSize + 2);
        }
    }

    return placeholderCanvas.toDataURL();
}

// Fetch owned squares from Firebase and group them into blocks
async function loadOwnedSquares() {
    loadingIndicator.style.display = 'block';
    try {
        const querySnapshot = await getDocs(collection(db, 'squares'));
        ownedSquares.clear();
        querySnapshot.forEach((doc) => {
            const [x, y] = doc.id.split('-').map(Number);
            ownedSquares.set(`${x}-${y}`, doc.data());
        });
    } catch (error) {
        console.error('Error loading squares:', error);
        ownedSquares.clear(); // Fallback to empty grid
    } finally {
        generatePlaceholders();
        drawGridLayer();
        drawBaseGrid();
        updateMainCanvas();
        loadingIndicator.style.display = 'none';
    }
}

// Generate placeholder blocks
function generatePlaceholders() {
    const blockWidth = gridWidth / blockSize;
    const blockHeight = gridHeight / blockSize;
    const totalBlocks = blockWidth * blockHeight;
    const placeholderCount = Math.floor(totalBlocks * 0.1);

    placeholderSquares.clear();
    for (let i = 0; i < placeholderCount; i++) {
        let blockX, blockY;
        do {
            blockX = Math.floor(Math.random() * blockWidth);
            blockY = Math.floor(Math.random() * blockHeight);
        } while (isBlockOwned(blockX, blockY) || placeholderSquares.has(`${blockX}-${blockY}`));

        const shape = Math.floor(Math.random() * 3);
        const color = `hsl(${Math.random() * 360}, 70%, 50%)`;
        const symbol = String.fromCharCode(65 + Math.floor(Math.random() * 26));

        const imageKey = `${shape}-${color}-${symbol}`;
        if (!placeholderImages.has(imageKey)) {
            placeholderImages.set(imageKey, preRenderPlaceholder(shape, color, symbol));
        }

        for (let x = blockX * blockSize; x < (blockX + 1) * blockSize; x++) {
            for (let y = blockY * blockSize; y < (blockY + 1) * blockSize; y++) {
                placeholderSquares.set(`${x}-${y}`, { blockX, blockY, shape, color, symbol, imageKey });
            }
        }
    }
}

function isBlockOwned(blockX, blockY) {
    for (let x = blockX * blockSize; x < (blockX + 1) * blockSize; x++) {
        for (let y = blockY * blockSize; y < (blockY + 1) * blockSize; y++) {
            if (ownedSquares.has(`${x}-${y}`)) return true;
        }
    }
    return false;
}

// Initialize grid
resizeGridCanvas();
window.addEventListener('resize', resizeGridCanvas);
loadOwnedSquares();

// Find the block dimensions for a given square
function findBlockBounds(startX, startY) {
    if (!ownedSquares.has(`${startX}-${startY}`)) return null;

    let minX = startX;
    let maxX = startX;
    let minY = startY;
    let maxY = startY;

    const owner = ownedSquares.get(`${startX}-${startY}`).owner;

    while (minX > 0 && ownedSquares.has(`${minX - 1}-${startY}`) && ownedSquares.get(`${minX - 1}-${startY}`).owner === owner) {
        minX--;
    }
    while (maxX < gridWidth - 1 && ownedSquares.has(`${maxX + 1}-${startY}`) && ownedSquares.get(`${maxX + 1}-${startY}`).owner === owner) {
        maxX++;
    }
    while (minY > 0 && ownedSquares.has(`${startX}-${minY - 1}`) && ownedSquares.get(`${startX}-${minY - 1}`).owner === owner) {
        minY--;
    }
    while (maxY < gridHeight - 1 && ownedSquares.has(`${startX}-${maxY + 1}`) && ownedSquares.get(`${startX}-${maxY + 1}`).owner === owner) {
        maxY++;
    }

    for (let x = minX; x <= maxX; x++) {
        for (let y = minY; y <= maxY; y++) {
            if (!ownedSquares.has(`${x}-${y}`) || ownedSquares.get(`${x}-${y}`).owner !== owner) {
                return null;
            }
        }
    }

    return { minX, maxX, minY, maxY, width: maxX - minX + 1, height: maxY - minY + 1 };
}

// Draw the base grid (grid lines + squares) on the base canvas
function drawBaseGrid() {
    baseCtx.clearRect(0, 0, baseCanvas.width, baseCanvas.height);
    baseCtx.drawImage(gridLayerCanvas, 0, 0);

    const drawnBlocks = new Set();
    const drawnImageBlocks = new Set();

    for (let x = 0; x < gridWidth; x++) {
        for (let y = 0; y < gridHeight; y++) {
            if (ownedSquares.has(`${x}-${y}`)) {
                const squareData = ownedSquares.get(`${x}-${y}`);
                const blockKey = `${x}-${y}`;

                if (squareData.imageUrl && !drawnImageBlocks.has(blockKey)) {
                    const block = findBlockBounds(x, y);
                    if (block) {
                        const { minX, minY, width, height } = block;
                        const blockKeyGroup = `${minX}-${minY}`;
                        if (!drawnImageBlocks.has(blockKeyGroup)) {
                            const img = new Image();
                            img.src = squareData.imageUrl;
                            img.onload = () => {
                                baseCtx.drawImage(
                                    img,
                                    minX * squareSize,
                                    minY * squareSize,
                                    width * squareSize,
                                    height * squareSize
                                );
                                updateMainCanvas();
                            };
                            img.onerror = () => {
                                console.error(`Failed to load image for block ${blockKeyGroup}: ${squareData.imageUrl}`);
                            };
                            drawnImageBlocks.add(blockKeyGroup);
                            for (let bx = minX; bx <= block.maxX; bx++) {
                                for (let by = minY; by <= block.maxY; by++) {
                                    drawnBlocks.add(`${bx}-${by}`);
                                }
                            }
                        }
                    }
                } else if (!drawnBlocks.has(blockKey)) {
                    baseCtx.fillStyle = squareData.forResale ? 'rgba(0, 255, 255, 0.5)' : 'rgba(255, 0, 0, 0.5)';
                    baseCtx.fillRect(x * squareSize, y * squareSize, squareSize, squareSize);

                    baseCtx.fillStyle = '#ffffff';
                    baseCtx.beginPath();
                    baseCtx.arc(x * squareSize + squareSize / 2, y * squareSize + squareSize / 2, squareSize / 3, 0, Math.PI * 2);
                    baseCtx.fill();
                    drawnBlocks.add(blockKey);
                }
            } else if (placeholderSquares.has(`${x}-${y}`)) {
                const { blockX, blockY, imageKey } = placeholderSquares.get(`${x}-${y}`);
                const blockKey = `${blockX}-${blockY}`;
                if (!drawnBlocks.has(blockKey)) {
                    const img = new Image();
                    img.src = placeholderImages.get(imageKey);
                    baseCtx.drawImage(img, blockX * blockSize * squareSize, blockY * blockSize * squareSize);
                    drawnBlocks.add(blockKey);
                }
            }
        }
    }
}

// Update the main canvas with the base state and any overlays
const updateMainCanvas = debounce(() => {
    gridCtx.clearRect(0, 0, gridCanvas.width, gridCanvas.height);
    gridCtx.drawImage(baseCanvas, 0, 0);

    if (isSelecting) {
        const minX = Math.min(selectionStartX, selectionEndX);
        const minY = Math.min(selectionStartY, selectionEndY);
        const maxX = Math.max(selectionStartX, selectionEndX);
        const maxY = Math.max(selectionStartY, selectionEndY);

        gridCtx.fillStyle = 'rgba(255, 255, 0, 0.3)';
        gridCtx.fillRect(minX * squareSize, minY * squareSize, (maxX - minX + 1) * squareSize, (maxY - minY + 1) * squareSize);
        gridCtx.strokeStyle = '#ffff00';
        gridCtx.lineWidth = 1;
        gridCtx.strokeRect(minX * squareSize, minY * squareSize, (maxX - minX + 1) * squareSize, (maxY - minY + 1) * squareSize);
    }

    if (isImageSelectionMode) {
        const ownedBlocks = new Set();
        for (let [key, value] of ownedSquares) {
            if (value.owner === auth.currentUser.email) {
                const block = findBlockBounds(...key.split('-').map(Number));
                if (block) {
                    const blockKey = `${block.minX}-${block.minY}`;
                    if (!ownedBlocks.has(blockKey)) {
                        gridCtx.fillStyle = 'rgba(0, 255, 255, 0.3)';
                        gridCtx.fillRect(
                            block.minX * squareSize,
                            block.minY * squareSize,
                            block.width * squareSize,
                            block.height * squareSize
                        );
                        gridCtx.strokeStyle = '#00ffff';
                        gridCtx.lineWidth = 1;
                        gridCtx.strokeRect(
                            block.minX * squareSize,
                            block.minY * squareSize,
                            block.width * squareSize,
                            block.height * squareSize
                        );
                        ownedBlocks.add(blockKey);
                    }
                }
            }
        }
    }

    if (isResellMode) {
        const resaleBlocks = new Set();
        for (let [key, value] of ownedSquares) {
            if (value.forResale) {
                const block = findBlockBounds(...key.split('-').map(Number));
                if (block) {
                    const blockKey = `${block.minX}-${block.minY}`;
                    if (!resaleBlocks.has(blockKey)) {
                        gridCtx.fillStyle = 'rgba(255, 215, 0, 0.3)';
                        gridCtx.fillRect(
                            block.minX * squareSize,
                            block.minY * squareSize,
                            block.width * squareSize,
                            block.height * squareSize
                        );
                        gridCtx.strokeStyle = '#ffd700';
                        gridCtx.lineWidth = 1;
                        gridCtx.strokeRect(
                            block.minX * squareSize,
                            block.minY * squareSize,
                            block.width * squareSize,
                            block.height * squareSize
                        );
                        gridCtx.font = '12px Courier New';
                        gridCtx.fillStyle = '#ffffff';
                        gridCtx.textAlign = 'center';
                        gridCtx.fillText(
                            `$${value.resalePrice}`,
                            block.minX * squareSize + (block.width * squareSize) / 2,
                            block.minY * squareSize + (block.height * squareSize) / 2
                        );
                        resaleBlocks.add(blockKey);
                    }
                }
            }
        }
    }
}, 50);

// Debounced hover effect
let lastBlockX = -1;
let lastBlockY = -1;
const drawHover = debounce((event) => {
    gridCtx.clearRect(0, 0, gridCanvas.width, gridCanvas.height);
    gridCtx.drawImage(baseCanvas, 0, 0);

    if (isSelecting || isImageSelectionMode || isResellMode) {
        updateMainCanvas();
        return;
    }

    const rect = gridCanvas.getBoundingClientRect();
    const mouseX = event.clientX - rect.left;
    const mouseY = event.clientY - rect.top;

    const container = document.getElementById('gridContainer');
    const scaleX = (gridWidth * squareSize) / container.clientWidth;
    const scaleY = (gridHeight * squareSize) / container.clientHeight;

    const gridX = Math.floor((mouseX * scaleX) / squareSize);
    const gridY = Math.floor((mouseY * scaleY) / squareSize);

    if (gridX >= 0 && gridX < gridWidth && gridY >= 0 && gridY < gridHeight) {
        const blockX = Math.floor(gridX / blockSize) * blockSize;
        const blockY = Math.floor(gridY / blockSize) * blockSize;
        lastBlockX = blockX;
        lastBlockY = blockY;

        const magSize = 100 / scaleX;

        gridCtx.fillStyle = 'rgba(0, 0, 0, 0.7)';
        gridCtx.fillRect(blockX * squareSize, blockY * squareSize, blockSize * squareSize, blockSize * squareSize);

        gridCtx.fillStyle = 'rgba(0, 255, 0, 0.2)';
        gridCtx.fillRect(blockX * squareSize, blockY * squareSize, magSize, magSize);
        gridCtx.strokeStyle = '#00ff00';
        gridCtx.lineWidth = 2;
        gridCtx.strokeRect(blockX * squareSize, blockY * squareSize, magSize, magSize);
        gridCtx.shadowColor = '#00ff00';
        gridCtx.shadowBlur = 10;

        if (ownedSquares.has(`${blockX}-${blockY}`)) {
            const squareData = ownedSquares.get(`${blockX}-${blockY}`);
            gridCtx.fillStyle = squareData.forResale ? 'rgba(255, 215, 0, 0.5)' : 'rgba(255, 0, 0, 0.5)';
            gridCtx.fillRect(blockX * squareSize, blockY * squareSize, magSize, magSize);

            gridCtx.fillStyle = '#ffffff';
            gridCtx.beginPath();
            gridCtx.arc(blockX * squareSize + magSize / 2, blockY * squareSize + magSize / 2, magSize / 3, 0, Math.PI * 2);
            gridCtx.fill();

            if (squareData.forResale) {
                gridCtx.font = '24px Courier New';
                gridCtx.fillStyle = '#ffffff';
                gridCtx.textAlign = 'center';
                gridCtx.fillText(`$${squareData.resalePrice}`, blockX * squareSize + magSize / 2, blockY * squareSize + magSize / 2 + 8);
            }
        } else if (placeholderSquares.has(`${blockX}-${blockY}`)) {
            const { shape, color, symbol } = placeholderSquares.get(`${blockX}-${blockY}`);
            gridCtx.fillStyle = color;
            gridCtx.beginPath();
            if (shape === 0) {
                gridCtx.arc(blockX * squareSize + magSize / 2, blockY * squareSize + magSize / 2, magSize / 3, 0, Math.PI * 2);
            } else if (shape === 1) {
                gridCtx.moveTo(blockX * squareSize + magSize / 2, blockY * squareSize + magSize / 6);
                gridCtx.lineTo(blockX * squareSize + magSize / 6, blockY * squareSize + 5 * magSize / 6);
                gridCtx.lineTo(blockX * squareSize + 5 * magSize / 6, blockY * squareSize + 5 * magSize / 6);
                gridCtx.closePath();
            } else {
                gridCtx.rect(blockX * squareSize + magSize / 4, blockY * squareSize + magSize / 4, magSize / 2, magSize / 2);
            }
            gridCtx.fill();

            gridCtx.font = '24px Courier New';
            gridCtx.fillStyle = '#000000';
            gridCtx.textAlign = 'center';
            gridCtx.fillText(symbol, blockX * squareSize + magSize / 2, blockY * squareSize + magSize / 2 + 8);
        }
    }
}, 50);

gridCanvas.addEventListener('mousemove', drawHover);

// Toggle between single block and multi-block modes
const toggleModeInput = document.getElementById('toggleMode');
toggleModeInput.addEventListener('change', () => {
    isMultiMode = toggleModeInput.checked;
    if (isImageSelectionMode) {
        isImageSelectionMode = false;
        pendingImageBlob = null;
        pendingImageUrl = null;
        updateMainCanvas();
    }
    if (isResellMode) {
        isResellMode = false;
        updateMainCanvas();
    }
});

// Mouse down for block selection
let clickStartTime;
gridCanvas.addEventListener('mousedown', (event) => {
    clickStartTime = Date.now();
});

// Mouse move for multi-block selection
gridCanvas.addEventListener('mousemove', (event) => {
    const rect = gridCanvas.getBoundingClientRect();
    const mouseX = event.clientX - rect.left;
    const mouseY = event.clientY - rect.top;

    const container = document.getElementById('gridContainer');
    const scaleX = (gridWidth * squareSize) / container.clientWidth;
    const scaleY = (gridHeight * square