const canvas = document.getElementById('gridCanvas');
const ctx = canvas.getContext('2d');

canvas.width = 500;
canvas.height = 500;
const gridSize = 100;
const squareSize = canvas.width / gridSize;

// Draw initial grid
ctx.strokeStyle = '#ffffff';
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

    // Magnify the square
    ctx.fillStyle = 'rgba(0, 255, 204, 0.3)'; // Cyan highlight
    ctx.fillRect(gridX * squareSize, gridY * squareSize, squareSize, squareSize);
    ctx.strokeStyle = '#00ffcc';
    ctx.lineWidth = 2;
    ctx.strokeRect(gridX * squareSize - 10, gridY * squareSize - 10, squareSize + 20, squareSize + 20);
});

// Redraw the grid without magnification
function redrawGrid() {
    ctx.strokeStyle = '#ffffff';
    ctx.lineWidth = 1;
    for (let x = 0; x < gridSize; x++) {
        for (let y = 0; y < gridSize; y++) {
            ctx.strokeRect(x * squareSize, y * squareSize, squareSize, squareSize);
        }
    }
}