<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1a365d',
                        secondary: '#e65100',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <nav class="bg-primary text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <a href="<?php echo URLROOT; ?>" class="text-xl font-bold">
                    <?php echo SITENAME; ?>
                </a>
                <div class="hidden md:flex space-x-4">
                    <a href="<?php echo URLROOT; ?>" class="hover:text-secondary transition">Home</a>
                    <a href="<?php echo URLROOT; ?>/blogs" class="hover:text-secondary transition">Blogs</a>
                    <a href="<?php echo URLROOT; ?>/forum" class="hover:text-secondary transition">Forum</a>
                    <a href="<?php echo URLROOT; ?>/contact" class="hover:text-secondary transition">Contact</a>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <div class="relative group">
                            <button class="flex items-center space-x-2 hover:text-secondary transition">
                                <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 hidden group-hover:block">
                                <?php if($_SESSION['is_admin']): ?>
                                    <a href="<?php echo URLROOT; ?>/admin" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                        Dashboard
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo URLROOT; ?>/profile" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                    Profiel
                                </a>
                                <a href="<?php echo URLROOT; ?>/logout" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                    Uitloggen
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo URLROOT; ?>/login" class="hover:text-secondary transition">Inloggen</a>
                        <a href="<?php echo URLROOT; ?>/register" 
                           class="bg-secondary px-4 py-2 rounded-lg hover:bg-opacity-90 transition">
                            Registreren
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav> 