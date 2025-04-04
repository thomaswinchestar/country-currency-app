const fs = require('fs');
const path = require('path');

// Directory to search for Vue files
const directory = path.join(__dirname, 'resources', 'js');

// Function to recursively find all Vue files
function findVueFiles(dir, fileList = []) {
    const files = fs.readdirSync(dir);
    
    files.forEach(file => {
        const filePath = path.join(dir, file);
        const stat = fs.statSync(filePath);
        
        if (stat.isDirectory()) {
            findVueFiles(filePath, fileList);
        } else if (file.endsWith('.vue')) {
            fileList.push(filePath);
        }
    });
    
    return fileList;
}

// Function to update imports in a file
function updateImports(filePath) {
    let content = fs.readFileSync(filePath, 'utf8');
    
    // Check if the file contains the old import
    if (content.includes('@inertiajs/inertia-vue3')) {
        console.log(`Updating: ${filePath}`);
        
        // Replace the imports
        content = content.replace(/@inertiajs\/inertia-vue3/g, '@inertiajs/vue3');
        
        // Write the updated content back to the file
        fs.writeFileSync(filePath, content, 'utf8');
        return true;
    }
    
    return false;
}

// Find all Vue files
const vueFiles = findVueFiles(directory);
let updatedCount = 0;

// Update imports in each file
vueFiles.forEach(file => {
    if (updateImports(file)) {
        updatedCount++;
    }
});

console.log(`Updated ${updatedCount} files`);
