<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Speaking Progress Components Test</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-6xl mx-auto space-y-12">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Speaking Progress Components</h1>
            <p class="text-gray-600">Testing circular progress, linear progress, and speaking streak components</p>
        </div>

        <!-- Circular Progress Indicators -->
        <section class="bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Circular Progress Indicators</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <x-circular-progress 
                        :percentage="15" 
                        level="Beginner"
                        :size="120"
                    />
                    <p class="text-sm text-gray-500 mt-2">Low Progress</p>
                </div>
                
                <div class="text-center">
                    <x-circular-progress 
                        :percentage="40" 
                        level="Elementary"
                        :size="120"
                    />
                    <p class="text-sm text-gray-500 mt-2">Medium-Low Progress</p>
                </div>
                
                <div class="text-center">
                    <x-circular-progress 
                        :percentage="65" 
                        level="Intermediate"
                        :size="120"
                    />
                    <p class="text-sm text-gray-500 mt-2">Medium Progress</p>
                </div>
                
                <div class="text-center">
                    <x-circular-progress 
                        :percentage="90" 
                        level="Advanced"
                        :size="120"
                    />
                    <p class="text-sm text-gray-500 mt-2">High Progress</p>
                </div>
            </div>
            
            <!-- Different sizes -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Different Sizes</h3>
                <div class="flex items-end justify-center space-x-8">
                    <x-circular-progress 
                        :percentage="75" 
                        level="Master"
                        :size="80"
                        :strokeWidth="6"
                    />
                    <x-circular-progress 
                        :percentage="75" 
                        level="Master"
                        :size="120"
                    />
                    <x-circular-progress 
                        :percentage="75" 
                        level="Master"
                        :size="160"
                        :strokeWidth="10"
                    />
                </div>
            </div>
        </section>

        <!-- Linear Progress Bars -->
        <section class="bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Linear Progress Bars</h2>
            
            <div class="space-y-6">
                <div>
                    <x-linear-progress 
                        label="Conversations Mastered"
                        :current="12"
                        :total="20"
                    />
                </div>
                
                <div>
                    <x-linear-progress 
                        label="Shadowing Exercises"
                        :current="8"
                        :total="15"
                    />
                </div>
                
                <div>
                    <x-linear-progress 
                        label="Phrases Learned"
                        :current="45"
                        :total="100"
                    />
                </div>
                
                <div>
                    <x-linear-progress 
                        label="Speaking Level Progress"
                        :current="750"
                        :total="1000"
                    />
                </div>
                
                <!-- Different heights -->
                <div class="pt-4 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Different Heights</h3>
                    <div class="space-y-4">
                        <x-linear-progress 
                            label="Thin Progress"
                            :current="60"
                            :total="100"
                            height="h-1"
                        />
                        <x-linear-progress 
                            label="Default Progress"
                            :current="60"
                            :total="100"
                            height="h-2"
                        />
                        <x-linear-progress 
                            label="Thick Progress"
                            :current="60"
                            :total="100"
                            height="h-4"
                        />
                    </div>
                </div>
                
                <!-- Without fraction -->
                <div class="pt-4 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Without Fraction Display</h3>
                    <x-linear-progress 
                        label="Overall Progress"
                        :current="35"
                        :total="100"
                        :showFraction="false"
                    />
                </div>
            </div>
        </section>

        <!-- Speaking Streak Display -->
        <section class="bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Speaking Streak Display</h2>
            
            <div class="space-y-6">
                <!-- 1 day streak -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">1 Day Streak</h3>
                    <x-speaking-streak 
                        :streak="1"
                        :weeklyProgress="[true, false, false, false, false, false, false]"
                    />
                </div>
                
                <!-- 5 day streak -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">5 Day Streak</h3>
                    <x-speaking-streak 
                        :streak="5"
                        :weeklyProgress="[true, true, true, true, true, false, false]"
                    />
                </div>
                
                <!-- 7 day streak (full week) -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">7 Day Streak (Full Week)</h3>
                    <x-speaking-streak 
                        :streak="7"
                        :weeklyProgress="[true, true, true, true, true, true, true]"
                    />
                </div>
                
                <!-- 15 day streak -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">15 Day Streak</h3>
                    <x-speaking-streak 
                        :streak="15"
                        :weeklyProgress="[true, true, false, true, true, true, true]"
                    />
                </div>
                
                <!-- 30+ day streak -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">30+ Day Streak</h3>
                    <x-speaking-streak 
                        :streak="35"
                        :weeklyProgress="[true, true, true, true, true, true, true]"
                    />
                </div>
                
                <!-- No streak -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">No Streak</h3>
                    <x-speaking-streak 
                        :streak="0"
                        :weeklyProgress="[false, false, false, false, false, false, false]"
                    />
                </div>
                
                <!-- Without weekly progress -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Without Weekly Progress</h3>
                    <x-speaking-streak 
                        :streak="10"
                        :showWeekly="false"
                    />
                </div>
            </div>
        </section>

        <!-- Combined Example (Dashboard-like) -->
        <section class="bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Combined Example (Dashboard View)</h2>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Circular progress -->
                <div class="flex justify-center">
                    <x-circular-progress 
                        :percentage="65" 
                        level="Intermediate"
                        :size="140"
                    />
                </div>
                
                <!-- Stats with linear progress -->
                <div class="lg:col-span-2 space-y-6">
                    <x-linear-progress 
                        label="Conversations Mastered"
                        :current="12"
                        :total="20"
                    />
                    
                    <x-linear-progress 
                        label="Shadowing Exercises"
                        :current="8"
                        :total="15"
                    />
                    
                    <x-speaking-streak 
                        :streak="5"
                        :weeklyProgress="[true, true, true, true, true, false, false]"
                    />
                </div>
            </div>
        </section>
    </div>
</body>
</html>
