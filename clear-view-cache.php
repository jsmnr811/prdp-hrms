<?php
/**
 * Force Clear View Cache Script
 * 
 * This script forcefully clears Laravel view cache files,
 * handling locked files by using Windows-specific file operations.
 * 
 * Run from project root: php clear-view-cache.php
 */

// Define the views cache directory
$viewsPath = __DIR__ . '/storage/framework/views';
$cachePath = __DIR__ . '/storage/framework/cache';

echo "============================================\n";
echo "Laravel View Cache Clearer - Windows Edition\n";
echo "============================================\n\n";

/**
 * Recursively delete files in a directory
 */
function clearDirectory($dir, $pattern = '*') {
    $count = 0;
    $errors = [];
    
    if (!is_dir($dir)) {
        return ['count' => 0, 'errors' => ["Directory does not exist: $dir"]];
    }
    
    $files = glob($dir . '/' . $pattern);
    
    foreach ($files as $file) {
        if (is_file($file)) {
            // Try multiple methods to delete the file
            $deleted = false;
            $error = '';
            
            // Method 1: Direct unlink
            if (@unlink($file)) {
                $deleted = true;
                $count++;
            } else {
                $error = error_get_last()['message'] ?? 'Unknown error';
                
                // Method 2: Try to close any existing handles by retrying
                for ($i = 0; $i < 3; $i++) {
                    usleep(100000); // 100ms delay
                    if (@unlink($file)) {
                        $deleted = true;
                        $count++;
                        $error = '';
                        break;
                    }
                }
                
                // Method 3: Use Windows command to force delete
                if (!$deleted) {
                    $escapedPath = str_replace('/', '\\', $dir);
                    $escapedFile = str_replace('/', '\\', $file);
                    $cmd = "del /F /Q \"$escapedFile\" 2>NUL";
                    exec($cmd, $output, $retVal);
                    if ($retVal === 0 || @unlink($file)) {
                        $deleted = true;
                        $count++;
                        $error = '';
                    }
                }
                
                // Method 4: Use PowerShell to force delete
                if (!$deleted) {
                    $escapedFile = str_replace('/', '\\', $file);
                    $cmd = "powershell -Command \"Remove-Item -Path '$escapedFile' -Force -ErrorAction SilentlyContinue\"";
                    exec($cmd, $output, $retVal);
                    if (@unlink($file)) {
                        $deleted = true;
                        $count++;
                        $error = '';
                    }
                }
            }
            
            if (!$deleted) {
                $errors[] = "Failed to delete: $file - $error";
            }
        } elseif (is_dir($file)) {
            // Recursively clear subdirectories (but not .gitignore)
            if (basename($file) !== '.gitignore') {
                $result = clearDirectory($file, $pattern);
                $count += $result['count'];
                $errors = array_merge($errors, $result['errors']);
            }
        }
    }
    
    return ['count' => $count, 'errors' => $errors];
}

/**
 * Kill PHP processes that might be locking files
 */
function killPhpProcesses() {
    echo "Checking for PHP processes...\n";
    
    // Get all PHP processes
    $output = [];
    exec('tasklist /FI "IMAGENAME eq php.exe" /FO CSV /NH', $output);
    
    $processCount = 0;
    foreach ($output as $line) {
        if (strpos($line, 'php.exe') !== false) {
            $processCount++;
        }
    }
    
    if ($processCount > 0) {
        echo "Found $processCount PHP processes running.\n";
        echo "WARNING: These processes may be locking view cache files.\n";
        echo "It is recommended to stop Laragon before clearing the cache.\n\n";
        
        echo "To stop Laragon:\n";
        echo "  1. Right-click Laragon icon in system tray\n";
        echo "  2. Select 'Stop All'\n";
        echo "  3. Or go to Laragon > Apache/NGINX > Stop\n\n";
        
        return $processCount;
    }
    
    echo "No PHP processes found running.\n\n";
    return 0;
}

// Main execution
echo "Step 1: Checking PHP processes\n";
echo "------------------------------\n";
killPhpProcesses();

echo "Step 2: Clearing view cache\n";
echo "------------------------------\n";

// Clear all files in views directory except .gitignore
$result = clearDirectory($viewsPath, '*.php');

echo "Deleted: " . $result['count'] . " files from views cache\n";

if (!empty($result['errors'])) {
    echo "\nErrors (some files may be locked):\n";
    foreach ($result['errors'] as $error) {
        echo "  - $error\n";
    }
    echo "\nTo remove locked files:\n";
    echo "  1. Stop Laragon (Apache/PHP)\n";
    echo "  2. Run this script again\n";
    echo "  3. Or manually delete files via File Explorer\n";
} else {
    echo "\n✓ All view cache files cleared successfully!\n";
}

echo "\nStep 3: Clearing framework cache\n";
echo "------------------------------\n";
$result2 = clearDirectory($cachePath, '*');
echo "Deleted: " . $result2['count'] . " files from framework cache\n";

echo "\n============================================\n";
echo "Cache clear complete!\n";
echo "============================================\n\n";

echo "Next steps:\n";
echo "1. If you had PHP processes running, restart Laragon\n";
echo "2. Run: php artisan view:clear\n";
echo "3. Run: php artisan config:clear\n";
echo "4. Run: php artisan cache:clear\n";
