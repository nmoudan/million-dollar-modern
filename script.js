const canvas = document.getElementById('gridCanvas');
const ctx = canvas.getContext('2d');

// Set canvas size (100x100 grid, smaller squares)
canvas.width = 500;
canvas.height = 500;
const gridSize = 100; // 100x100 grid
const squareSize = canvas.width / gridSize; // 5px per square

// Draw the grid
ctx.strokeStyle = '#ffffff'; // White borders
for (let x = 0; x < gridSize; x++) {
    for (let y = 0; y < gridSize; y++) {
        ctx.strokeRect(x * squareSize, y * squareSize, squareSize, squareSize);
    }
}