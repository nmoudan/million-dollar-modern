const canvas = document.getElementById('gridCanvas');
const ctx = canvas.getContext('2d');

canvas.width = 500;
canvas.height = 500;
const gridSize = 100; // 100x100 grid
const squareSize = canvas.width / gridSize;

// Draw initial grid
ctx.strokeStyle = '#00ff00'; // Neon green
ctx.lineWidth = 0.5;
for (let x = 0; x < gridSize; x++) {
    for (let y = 0; y < gridSize; y++) {
        ctx.strokeRect(x * squareSize, y * squareSize, squareSize, squareSize);
    }
}

// Hover effect
canvas.addEventListener('mousemove', (event) => {
    // Clear previous magnification
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    redrawGrid();

    // Get mouse position
    const rect = canvas.getBoundingClientRect();
    const mouseX = event.clientX - rect.left;
    const mouseY = event.clientY - rect.top;

    // Find the square under the mouse
    const gridX = Math.floor(mouseX / squareSize);
    const gridY = Math.floor(mouseY / squareSize);

    // Magnify the square with neon effect
    ctx.fillStyle = 'rgba(0, 255, 0, 0.2)'; // Faint green fill
    ctx.fillRect(gridX * squareSize, gridY * squareSize, squareSize, squareSize);
    ctx.strokeStyle = '#00ff00';
    ctx.lineWidth = 2;
    ctx.strokeRect(gridX * squareSize - 15, gridY * squareSize - 15, squareSize + 30, squareSize + 30); // Larger magnification
    ctx.shadowColor = '#00ff00';
    ctx.shadowBlur = 10; // Glowing effect
});

// Redraw the grid without magnification
function redrawGrid() {
    ctx.strokeStyle = '#00ff00';
    ctx.lineWidth = 0.5;
    ctx.shadowBlur = 0; // Reset glow for normal grid
    for (let x = 0; x < gridSize; x++) {
        for (let y = 0; y < gridSize; y++) {
            ctx.strokeRect(x * squareSize, y * squareSize, squareSize, squareSize);
        }
    }
}

// Reset shadow when mouse leaves
canvas.addEventListener('mouseout', () => {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    redrawGrid();
});