<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversation Card Component Test</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 p-4 md:p-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Conversation Card Component</h1>
        <p class="text-gray-600 mb-8">Testing all card states: available, in-progress, completed, and locked</p>
        
        {{-- Desktop: 3-column grid, Tablet: 2-column, Mobile: 1-column --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            {{-- Available State --}}
            <x-conversation-card 
                :lesson="(object)[
                    'order' => 1,
                    'title' => 'Asking for Directions',
                    'description' => 'Speak confidently when navigating Japanese cities',
                    'slug' => 'asking-for-directions',
                    'dialogues' => (object)['count' => fn() => 5],
                    'shadowingExercises' => (object)['count' => fn() => 3],
                ]"
                status="available"
            />
            
            {{-- In-Progress State --}}
            <x-conversation-card 
                :lesson="(object)[
                    'order' => 2,
                    'title' => 'Ordering Food',
                    'description' => 'Order meals and drinks like a local at restaurants',
                    'slug' => 'ordering-food',
                    'dialogues' => (object)['count' => fn() => 6],
                    'shadowingExercises' => (object)['count' => fn() => 4],
                ]"
                :progress="(object)[
                    'completion_percentage' => 45.5
                ]"
                status="in-progress"
            />
            
            {{-- Completed State --}}
            <x-conversation-card 
                :lesson="(object)[
                    'order' => 3,
                    'title' => 'Making Small Talk',
                    'description' => 'Have casual conversations about weather, hobbies, and daily life',
                    'slug' => 'making-small-talk',
                    'dialogues' => (object)['count' => fn() => 7],
                    'shadowingExercises' => (object)['count' => fn() => 5],
                ]"
                :progress="(object)[
                    'completion_percentage' => 100
                ]"
                status="completed"
            />
            
            {{-- Locked State --}}
            <x-conversation-card 
                :lesson="(object)[
                    'order' => 4,
                    'title' => 'Shopping & Bargaining',
                    'description' => 'Navigate stores and markets with confidence',
                    'slug' => 'shopping-bargaining',
                    'dialogues' => (object)['count' => fn() => 5],
                    'shadowingExercises' => (object)['count' => fn() => 3],
                ]"
                :prerequisiteLesson="(object)[
                    'title' => 'Making Small Talk'
                ]"
                status="locked"
            />
            
            {{-- Another Available State --}}
            <x-conversation-card 
                :lesson="(object)[
                    'order' => 5,
                    'title' => 'At the Doctor',
                    'description' => 'Describe symptoms and understand medical advice',
                    'slug' => 'at-the-doctor',
                    'dialogues' => (object)['count' => fn() => 4],
                    'shadowingExercises' => (object)['count' => fn() => 2],
                ]"
                status="available"
            />
            
            {{-- Another In-Progress State --}}
            <x-conversation-card 
                :lesson="(object)[
                    'order' => 6,
                    'title' => 'Using Public Transport',
                    'description' => 'Navigate trains, buses, and taxis with ease',
                    'slug' => 'using-public-transport',
                    'dialogues' => (object)['count' => fn() => 6],
                    'shadowingExercises' => (object)['count' => fn() => 4],
                ]"
                :progress="(object)[
                    'completion_percentage' => 75.0
                ]"
                status="in-progress"
            />
            
        </div>
        
        {{-- Mobile Optimization Notes --}}
        <div class="mt-12 p-6 bg-white rounded-lg border border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 mb-3">Mobile Optimization Features</h2>
            <ul class="space-y-2 text-gray-700">
                <li class="flex items-start gap-2">
                    <span class="text-green-500 mt-1">✓</span>
                    <span><strong>Single-column layout on mobile:</strong> Cards stack vertically on screens < 768px</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-green-500 mt-1">✓</span>
                    <span><strong>Touch-friendly targets:</strong> All buttons are 48px+ height (py-3 = 12px padding + text)</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-green-500 mt-1">✓</span>
                    <span><strong>Optimized spacing:</strong> Proper padding (p-6 = 24px) and gap (gap-6 = 24px) for mobile</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-green-500 mt-1">✓</span>
                    <span><strong>Full-width buttons:</strong> CTA buttons span full card width for easy tapping</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-green-500 mt-1">✓</span>
                    <span><strong>Readable text:</strong> Minimum 14px (text-sm) for all body text</span>
                </li>
            </ul>
        </div>
        
        {{-- Card States Documentation --}}
        <div class="mt-6 p-6 bg-white rounded-lg border border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 mb-3">Card States</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">🟢 Available</h3>
                    <p class="text-sm text-gray-600">White background, gray border, "Practice Speaking" button</p>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">🔵 In-Progress</h3>
                    <p class="text-sm text-gray-600">Blue border (2px), progress bar, "Continue Speaking" button</p>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">✅ Completed</h3>
                    <p class="text-sm text-gray-600">Green border, checkmark badge, 100% progress, "Review Conversation" button</p>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">🔒 Locked</h3>
                    <p class="text-sm text-gray-600">Gray background, lock icon, prerequisite message, disabled button</p>
                </div>
            </div>
        </div>
        
        {{-- Hover Effects Note --}}
        <div class="mt-6 p-6 bg-indigo-50 rounded-lg border border-indigo-200">
            <h2 class="text-xl font-bold text-indigo-900 mb-2">✨ Hover Effects</h2>
            <p class="text-indigo-800">
                Hover over available, in-progress, or completed cards to see the lift animation (-translate-y-1) and enhanced shadow. 
                Locked cards do not have hover effects.
            </p>
        </div>
    </div>
</body>
</html>
