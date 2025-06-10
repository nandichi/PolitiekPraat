<?php require_once 'views/templates/header.php'; ?>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Hero Section -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-primary/10 via-secondary/10 to-primary/10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-24">
            <div class="text-center">
                <!-- Icon Container -->
                <div class="flex justify-center mb-8">
                    <div class="relative">
                        <div class="w-24 h-24 bg-gradient-to-br from-primary to-secondary rounded-3xl flex items-center justify-center shadow-2xl transform rotate-3 hover:rotate-6 transition-all duration-500">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-yellow-400 rounded-full flex items-center justify-center">
                            <span class="text-xs font-bold text-yellow-800">NL</span>
                        </div>
                    </div>
                </div>

                <!-- Title -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black mb-6">
                    <span class="bg-gradient-to-r from-primary via-secondary to-primary bg-clip-text text-transparent">
                        Programma Vergelijker
                    </span>
                </h1>

                <!-- Subtitle -->
                <p class="text-lg sm:text-xl text-slate-600 max-w-4xl mx-auto leading-relaxed mb-8 px-4">
                    Vergelijk de standpunten van Nederlandse politieke partijen over belangrijke thema's. 
                    Ontdek waar partijen overeenkomen en waar ze van elkaar verschillen.
                </p>

                <!-- Stats -->
                <div class="flex flex-wrap justify-center gap-8 mb-12">
                    <div class="text-center">
                        <div class="text-2xl sm:text-3xl font-bold text-primary"><?php echo count($data['parties']); ?></div>
                        <div class="text-sm text-slate-500 font-medium">Partijen</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl sm:text-3xl font-bold text-secondary"><?php echo count($data['themes']); ?></div>
                        <div class="text-sm text-slate-500 font-medium">Thema's</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl sm:text-3xl font-bold text-primary">150</div>
                        <div class="text-sm text-slate-500 font-medium">Zetels</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Comparison Tool -->
    <section class="relative -mt-12 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-2xl border border-slate-200/50 overflow-hidden backdrop-blur-sm">
                
                <!-- Selection Panel -->
                <div class="bg-gradient-to-br from-slate-50 to-blue-50/30 p-6 sm:p-8 lg:p-10">
                    <div class="grid lg:grid-cols-2 gap-8 lg:gap-12">
                        
                        <!-- Party Selection -->
                        <div class="space-y-6">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-primary to-secondary rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl sm:text-2xl font-bold text-slate-800">Selecteer Partijen</h3>
                                    <p class="text-slate-500 text-sm">Kies minimaal 2 partijen om te vergelijken</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="party-selector">
                                <?php foreach($data['parties'] as $key => $party): ?>
                                    <label class="group relative flex items-center p-4 rounded-2xl bg-white border-2 border-slate-200 
                                                 hover:border-blue-300 hover:shadow-lg cursor-pointer transition-all duration-300 
                                                 hover:scale-[1.02] focus-within:border-blue-400 focus-within:ring-4 focus-within:ring-blue-100">
                                        <input type="checkbox" 
                                               class="party-checkbox sr-only" 
                                                                                      data-party="<?php echo $key; ?>" 
                                       data-color="<?php echo $party['color']; ?>">
                                
                                <!-- Custom Checkbox -->
                                <div class="w-5 h-5 border-2 border-slate-300 rounded-lg mr-4 flex items-center justify-center
                                           group-hover:border-primary transition-colors duration-200 flex-shrink-0">
                                    <svg class="w-3 h-3 text-primary opacity-0 group-has-[:checked]:opacity-100 transition-opacity duration-200" 
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                        
                                        <div class="flex items-center space-x-3 flex-1 min-w-0">
                                            <img src="<?php echo $party['logo']; ?>" 
                                                 alt="<?php echo $party['name']; ?>" 
                                                 class="w-10 h-10 rounded-xl object-cover border-2 border-slate-200 
                                                       group-hover:border-slate-300 transition-all duration-300"
                                                 onerror="this.style.display='none'">
                                            <div class="flex-1 min-w-0">
                                                                                            <div class="font-semibold text-slate-800 group-hover:text-primary 
                                                       transition-colors duration-300 truncate text-sm sm:text-base">
                                                <?php echo $key; ?>
                                            </div>
                                                <div class="text-xs text-slate-500">
                                                    <?php echo $party['current_seats']; ?> zetels
                                                </div>
                                            </div>
                                        </div>
                                        
                                                                            <!-- Selection Indicator -->
                                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-primary/10 to-secondary/10 
                                               opacity-0 group-has-[:checked]:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Theme Selection -->
                        <div class="space-y-6">
                            <div class="flex items-center space-x-4">
                                                                 <div class="w-12 h-12 bg-gradient-to-br from-secondary to-primary rounded-2xl flex items-center justify-center shadow-lg">
                                     <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                               d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                     </svg>
                                 </div>
                                <div>
                                    <h3 class="text-xl sm:text-2xl font-bold text-slate-800">Selecteer Thema's</h3>
                                    <p class="text-slate-500 text-sm">Kies de onderwerpen die je wilt vergelijken</p>
                                </div>
                            </div>
                            
                            <div class="space-y-3" id="theme-selector">
                                <?php foreach($data['themes'] as $key => $theme): ?>
                                    <label class="group relative flex items-center p-4 rounded-2xl bg-white border-2 border-slate-200 
                                                 hover:border-indigo-300 hover:shadow-lg cursor-pointer transition-all duration-300 
                                                 hover:scale-[1.02] focus-within:border-indigo-400 focus-within:ring-4 focus-within:ring-indigo-100">
                                        <input type="checkbox" 
                                               class="theme-checkbox sr-only" 
                                               data-theme="<?php echo $key; ?>">
                                        
                                                                                 <!-- Custom Checkbox -->
                                         <div class="w-5 h-5 border-2 border-slate-300 rounded-lg mr-4 flex items-center justify-center
                                                    group-hover:border-secondary transition-colors duration-200 flex-shrink-0">
                                             <svg class="w-3 h-3 text-secondary opacity-0 group-has-[:checked]:opacity-100 transition-opacity duration-200" 
                                                  fill="currentColor" viewBox="0 0 20 20">
                                                 <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                             </svg>
                                         </div>
                                        
                                        <div class="flex items-center space-x-4 flex-1 min-w-0">
                                            <span class="text-2xl sm:text-3xl group-hover:scale-110 transition-transform duration-300 flex-shrink-0">
                                                <?php echo $theme['icon']; ?>
                                            </span>
                                            <div class="flex-1 min-w-0">
                                                                                                 <div class="font-semibold text-slate-800 group-hover:text-secondary 
                                                            transition-colors duration-300 text-sm sm:text-base">
                                                     <?php echo $theme['title']; ?>
                                                 </div>
                                                <p class="text-xs sm:text-sm text-slate-500 mt-1 line-clamp-2">
                                                    <?php echo $theme['description']; ?>
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <!-- Selection Indicator -->
                                        <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-indigo-500/10 to-purple-500/10 
                                                   opacity-0 group-has-[:checked]:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Compare Button -->
                    <div class="mt-10 text-center">
                        <button id="compare-btn" 
                                class="group inline-flex items-center px-8 py-4 bg-gradient-to-r from-primary via-secondary to-primary 
                                      text-white font-bold rounded-2xl shadow-xl hover:shadow-2xl 
                                      transform hover:scale-105 active:scale-95 transition-all duration-300 
                                      disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none
                                      disabled:hover:shadow-xl text-sm sm:text-base">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-3 group-hover:rotate-12 transition-transform duration-300" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <span id="button-text">Vergelijk Standpunten</span>
                        </button>
                        <p class="text-xs text-slate-500 mt-2">Selecteer minimaal 2 partijen en 1 thema</p>
                    </div>
                </div>

                <!-- Comparison Results -->
                <div id="comparison-results" class="hidden">
                    <div class="border-t border-slate-200 p-6 sm:p-8 lg:p-10">
                        <div id="results-content"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Compare Section -->
    <section class="py-20 bg-gradient-to-br from-slate-100 to-blue-100/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-800 mb-6">
                    Snelle Vergelijkingen
                </h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                    Populaire vergelijkingen om snel inzicht te krijgen in belangrijke verschillen
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-8">
                <!-- Grootste Partijen -->
                <div class="group bg-white rounded-3xl shadow-xl border border-slate-200/50 p-8 
                           hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 hover:scale-[1.02]">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 
                                   rounded-3xl mb-6 shadow-2xl group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold text-slate-800 mb-3">Grootste Partijen</h3>
                        <p class="text-slate-600 mb-6 text-sm sm:text-base">
                            Vergelijk de standpunten van PVV, GL-PvdA en VVD op kernthema's
                        </p>
                        <div class="flex flex-wrap justify-center gap-2 mb-6">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">Immigratie</span>
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Klimaat</span>
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">Zorg</span>
                        </div>
                        <button class="quick-compare w-full bg-gradient-to-r from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 
                                      text-blue-700 font-semibold py-4 px-6 rounded-2xl transition-all duration-300 
                                      transform hover:scale-105 active:scale-95 group-hover:shadow-lg text-sm sm:text-base" 
                                data-parties="PVV,GL-PvdA,VVD" 
                                data-themes="Immigratie,Klimaat,Zorg">
                            <span class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Vergelijk Nu
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Links vs Rechts -->
                <div class="group bg-white rounded-3xl shadow-xl border border-slate-200/50 p-8 
                           hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 hover:scale-[1.02]">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-red-500 via-purple-500 to-green-500 
                                   rounded-3xl mb-6 shadow-2xl group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold text-slate-800 mb-3">Links vs Rechts</h3>
                        <p class="text-slate-600 mb-6 text-sm sm:text-base">
                            Ontdek de verschillen tussen linkse en rechtse partijen
                        </p>
                        <div class="flex flex-wrap justify-center gap-2 mb-6">
                            <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium">Economie</span>
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">Zorg</span>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium">Onderwijs</span>
                        </div>
                        <button class="quick-compare w-full bg-gradient-to-r from-purple-50 to-purple-100 hover:from-purple-100 hover:to-purple-200 
                                      text-purple-700 font-semibold py-4 px-6 rounded-2xl transition-all duration-300 
                                      transform hover:scale-105 active:scale-95 group-hover:shadow-lg text-sm sm:text-base" 
                                data-parties="SP,GL-PvdA,VVD,PVV" 
                                data-themes="Economie,Zorg,Onderwijs">
                            <span class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Vergelijk Nu
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Klimaat & Duurzaamheid -->
                <div class="group bg-white rounded-3xl shadow-xl border border-slate-200/50 p-8 
                           hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 hover:scale-[1.02] md:col-span-2 xl:col-span-1">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-green-500 to-emerald-600 
                                   rounded-3xl mb-6 shadow-2xl group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold text-slate-800 mb-3">Klimaat & Energie</h3>
                        <p class="text-slate-600 mb-6 text-sm sm:text-base">
                            Vergelijk alle partijen op duurzaamheid en energietransitie
                        </p>
                        <div class="flex flex-wrap justify-center gap-2 mb-6">
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Klimaat</span>
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">Energie</span>
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium">Duurzaamheid</span>
                        </div>
                        <button class="quick-compare w-full bg-gradient-to-r from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 
                                      text-green-700 font-semibold py-4 px-6 rounded-2xl transition-all duration-300 
                                      transform hover:scale-105 active:scale-95 group-hover:shadow-lg text-sm sm:text-base" 
                                data-parties="GL-PvdA,D66,PvdD,VVD,PVV" 
                                data-themes="Klimaat,Energie,Duurzaamheid">
                            <span class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Vergelijk Nu
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
/* Custom Checkbox Styles */
.group:has(.party-checkbox:checked) {
    @apply border-blue-400 shadow-lg;
}

.group:has(.theme-checkbox:checked) {
    @apply border-indigo-400 shadow-lg;
}

.group:has(.party-checkbox:checked) .w-5.h-5 {
    @apply bg-blue-600 border-blue-600;
}

.group:has(.theme-checkbox:checked) .w-5.h-5 {
    @apply bg-indigo-600 border-indigo-600;
}

/* Line clamp utility */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Loading animation */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.loading {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Responsive improvements */
@media (max-width: 640px) {
    .text-4xl { font-size: 2.5rem; }
    .text-5xl { font-size: 3rem; }
    .text-6xl { font-size: 3.5rem; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const parties = <?php echo json_encode($data['parties']); ?>;
    const themes = <?php echo json_encode($data['themes']); ?>;
    
    const compareBtn = document.getElementById('compare-btn');
    const resultsDiv = document.getElementById('comparison-results');
    const resultsContent = document.getElementById('results-content');
    const buttonText = document.getElementById('button-text');
    
    // Quick compare functionality
    document.querySelectorAll('.quick-compare').forEach(button => {
        button.addEventListener('click', function() {
            const selectedParties = this.dataset.parties.split(',');
            const selectedThemes = this.dataset.themes.split(',');
            
            // Clear all checkboxes first
            document.querySelectorAll('.party-checkbox, .theme-checkbox').forEach(cb => {
                cb.checked = false;
            });
            
            // Check the selected checkboxes
            document.querySelectorAll('.party-checkbox').forEach(cb => {
                cb.checked = selectedParties.includes(cb.dataset.party);
            });
            
            document.querySelectorAll('.theme-checkbox').forEach(cb => {
                cb.checked = selectedThemes.includes(cb.dataset.theme);
            });
            
            // Trigger comparison
            updateCompareButton();
            performComparison();
            
            // Smooth scroll to comparison tool
            document.querySelector('#comparison-results').scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start',
                inline: 'nearest'
            });
        });
    });
    
    // Enable/disable compare button
    function updateCompareButton() {
        const selectedParties = Array.from(document.querySelectorAll('.party-checkbox:checked'));
        const selectedThemes = Array.from(document.querySelectorAll('.theme-checkbox:checked'));
        
        const canCompare = selectedParties.length >= 2 && selectedThemes.length >= 1;
        compareBtn.disabled = !canCompare;
        
        if (canCompare) {
            buttonText.textContent = `Vergelijk ${selectedParties.length} partijen op ${selectedThemes.length} thema${selectedThemes.length === 1 ? '' : 's'}`;
        } else {
            buttonText.textContent = 'Vergelijk Standpunten';
        }
    }
    
    // Listen for checkbox changes
    document.querySelectorAll('.party-checkbox, .theme-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateCompareButton);
    });
    
    // Perform comparison
    function performComparison() {
        const selectedParties = Array.from(document.querySelectorAll('.party-checkbox:checked'))
            .map(cb => cb.dataset.party);
        const selectedThemes = Array.from(document.querySelectorAll('.theme-checkbox:checked'))
            .map(cb => cb.dataset.theme);
        
        if (selectedParties.length < 2 || selectedThemes.length < 1) {
            return;
        }
        
        // Show loading state
        buttonText.textContent = 'Vergelijking laden...';
        compareBtn.disabled = true;
        
        setTimeout(() => {
            let html = '<div class="space-y-8">';
            
            selectedThemes.forEach((themeKey, themeIndex) => {
                const theme = themes[themeKey];
                html += `
                    <div class="bg-gradient-to-br from-slate-50 to-blue-50/30 rounded-3xl p-6 sm:p-8 border border-slate-200/50 
                             shadow-lg hover:shadow-xl transition-all duration-300" 
                         style="animation-delay: ${themeIndex * 100}ms">
                        <div class="flex flex-col sm:flex-row sm:items-center mb-8 space-y-4 sm:space-y-0">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl 
                                           flex items-center justify-center shadow-lg">
                                    <span class="text-2xl">${theme.icon}</span>
                                </div>
                                <div>
                                    <h3 class="text-2xl sm:text-3xl font-bold text-slate-800">${theme.title}</h3>
                                    <p class="text-slate-600 text-sm sm:text-base">${theme.description}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid gap-4 lg:gap-6">
                `;
                
                selectedParties.forEach((partyKey, partyIndex) => {
                    const party = parties[partyKey];
                    const standpoint = party.standpoints[themeKey] || 'Geen specifiek standpunt bekend voor dit thema.';
                    
                    html += `
                        <div class="bg-white rounded-2xl p-4 sm:p-6 border-l-4 shadow-md hover:shadow-lg 
                                   transition-all duration-300 transform hover:scale-[1.01]" 
                             style="border-left-color: ${party.color}; animation-delay: ${(themeIndex * selectedParties.length + partyIndex) * 50}ms">
                            <div class="flex flex-col sm:flex-row sm:items-start space-y-3 sm:space-y-0 sm:space-x-4">
                                <img src="${party.logo}" alt="${party.name}" 
                                     class="w-14 h-14 rounded-xl object-cover border-2 border-slate-200 flex-shrink-0 mx-auto sm:mx-0"
                                     onerror="this.style.display='none'">
                                <div class="flex-1 text-center sm:text-left">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3">
                                        <div>
                                            <h4 class="font-bold text-slate-800 text-lg">${party.name}</h4>
                                            <div class="flex items-center justify-center sm:justify-start space-x-2 text-sm text-slate-500 mt-1">
                                                <span>${party.current_seats} zetels</span>
                                                <span class="w-2 h-2 rounded-full" style="background-color: ${party.color}"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="prose prose-sm max-w-none">
                                        <p class="text-slate-700 leading-relaxed text-sm sm:text-base">${standpoint}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                html += `
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            
            resultsContent.innerHTML = html;
            resultsDiv.classList.remove('hidden');
            
            // Reset button
            updateCompareButton();
            
            // Smooth scroll to results
            setTimeout(() => {
                resultsDiv.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start',
                    inline: 'nearest'
                });
            }, 100);
            
        }, 500);
    }
    
    // Compare button click
    compareBtn.addEventListener('click', performComparison);
    
    // Initial state
    updateCompareButton();
    
    // Add animation classes for staggered entrance
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observe all animatable elements
    document.querySelectorAll('[data-aos]').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
});
</script>

<?php require_once 'views/templates/footer.php'; ?> 