<!-- Beta Modal -->
    <div id="betaModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeBetaModal()"></div>
        
        <!-- Modal panel -->
        <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl transform transition-all duration-300">
            <!-- Header -->
            <div class="flex items-center justify-between p-6 pb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-secondary to-primary rounded-full flex items-center justify-center shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">
                        Website in 
                        <span class="bg-gradient-to-r from-secondary to-primary bg-clip-text text-transparent">BETA</span>
                    </h3>
                </div>
                <button onclick="closeBetaModal()" 
                        class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Content -->
            <div class="px-6 pb-6">
                <!-- Welcome message -->
                <div class="bg-gradient-to-r from-primary/8 to-secondary/8 rounded-xl p-5 mb-6">
                    <p class="text-gray-700 leading-relaxed text-center">
                        Welkom bij <strong class="text-primary">PolitiekPraat</strong>! Onze website bevindt zich momenteel nog in de beta fase. 
                        Dit betekent dat we hard werken aan nieuwe functies en verbeteringen.
                    </p>
                </div>
                
                <!-- Info sections -->
                <div class="space-y-4 mb-6">
                    <div class="flex items-start space-x-4 p-4 rounded-xl bg-gray-50">
                        <div class="w-8 h-8 bg-secondary/10 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-secondary" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 mb-1">Wat betekent dit?</h4>
                            <p class="text-sm text-gray-600 leading-relaxed">Je kunt mogelijk kleine bugs tegenkomen of functies die nog niet perfect werken.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4 p-4 rounded-xl bg-gray-50">
                        <div class="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 mb-1">Jouw feedback is waardevol!</h4>
                            <p class="text-sm text-gray-600 leading-relaxed">Help ons de website te verbeteren door je ervaring te delen.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <button onclick="closeBetaModal()" 
                            class="px-6 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 rounded-lg transition-all duration-200">
                        Sluiten
                    </button>
                    <a href="<?php echo URLROOT; ?>/contact" 
                       class="inline-flex items-center px-6 py-2.5 text-sm font-medium text-white 
                              bg-gradient-to-r from-secondary to-primary rounded-lg 
                              hover:from-primary hover:to-secondary transition-all duration-300 
                              hover:scale-105 shadow-lg hover:shadow-xl">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Feedback geven
                    </a>
                </div>
            </div>
        </div>
    </div>

    