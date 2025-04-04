import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

// Get the directory name
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

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
    if (content.includes('@inertiajs/inertia')) {
        console.log(`Updating: ${filePath}`);
        
        // If the file has both imports, we need to combine them
        if (content.includes("import { Inertia } from '@inertiajs/inertia'") && 
            content.includes("import { ") && 
            content.includes(" } from '@inertiajs/vue3'")) {
            
            // Get the existing imports from vue3
            const vue3ImportMatch = content.match(/import \{ (.*?) \} from '@inertiajs\/vue3'/);
            if (vue3ImportMatch && vue3ImportMatch[1]) {
                const existingImports = vue3ImportMatch[1];
                // Replace with combined imports
                content = content.replace(
                    /import \{ (.*?) \} from '@inertiajs\/vue3'/,
                    `import { ${existingImports}, Inertia } from '@inertiajs/vue3'`
                );
                // Remove the separate inertia import
                content = content.replace(/import \{ Inertia \} from '@inertiajs\/inertia';?\n/, '');
            }
        } else {
            // Just replace the import
            content = content.replace(
                /import \{ Inertia \} from '@inertiajs\/inertia'/g, 
                "import { Inertia } from '@inertiajs/vue3'"
            );
        }
        
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
