<?php

namespace app\Http\Controllers;

use app\Services\FinAssistService;
use app\Models\AnalysisConversations;
use app\Models\AnalysisMessages;
use Illuminate\Http\Request;
use app\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class FinAssistController extends Controller
{
    protected $finAssistService;

    public function __construct(FinAssistService $finAssistService)
    {
        $this->finAssistService = $finAssistService;
    }
    public function index()
{
    // Fetch past conversations with messages
    $conversations = AnalysisConversations::with('messages')->latest()->get();
    return view('finAssist.finassist', compact('conversations'));
}

    public function handleQuery(Request $request)
    {
        $userInput = $request->input('message');
    
        if (!$userInput) {
            return response()->json(['error' => 'No message provided'], 400);
        }
    
        // Start or get current conversation
        $conversation = AnalysisConversations::firstOrCreate(['user_id' => auth()->id()]);

        // Save user message
        $userMessage = new AnalysisMessages([
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'content' => $userInput
        ]);
        $userMessage->save();
    
        // Handle greetings
        if ($this->isGreeting($userInput)) {
            $botResponse = $this->handleGreeting(auth()->user()->name);
            $this->saveBotMessage($conversation->id, $botResponse);
            return response()->json(['response' => $botResponse]);
        }
    
        // Handle predefined financial queries
        $predefinedResponse = $this->handlePredefinedQueries($userInput);
        if ($predefinedResponse) {
            $this->saveBotMessage($conversation->id, $predefinedResponse);
            return response()->json(['response' => $predefinedResponse]);
        }
    
        // Call OpenAI API for other queries
        try {
            $response = Http::timeout(10) // Set a timeout of 10 seconds
                ->withHeaders([
                    'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                    'Content-Type' => 'application/json'
                ])->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an AI assistant for wense inventory.'],
                        ['role' => 'user', 'content' => $userInput]
                    ],
                    'temperature' => 0.7
                ]);
    
            if ($response->failed()) {
                throw new \Exception('API request failed');
            }
    
            $botResponse = $response->json()['choices'][0]['message']['content'] ?? 'Something went wrong, please try again.';
        } catch (\Exception $e) {
            Log::error('FinAssist API Error: ' . $e->getMessage());
            $botResponse = 'FinAssist is currently unavailable. Please try again later.';
        }
    
        // Save bot response
        $this->saveBotMessage($conversation->id, $botResponse);
    
        return response()->json(['response' => $botResponse]);
    }

    private function isGreeting($message)
    {
        $greetings = ['hi', 'hello', 'hey', 'good morning', 'good afternoon', 'good evening'];
        foreach ($greetings as $greeting) {
            if (stripos($message, $greeting) !== false) {
                return true;
            }
        }
        return false;
    }

    private function handleGreeting($userName)
    {
        return "Hello $userName! FinAssist is currently busy reading some finance books. Please try again later.";
    }

    private function handlePredefinedQueries($message)
    {
        $predefinedResponses = [
            'sales forecast' => 'Based on historical data, your sales forecast shows steady growth. Consider optimizing high-performing products.',
            'stock reorder' => 'It looks like some items are running low. Would you like me to generate a reorder list?',
            'profit margin' => 'Your current profit margin is 25%. To improve, consider adjusting pricing or reducing costs.',
            'debts' => 'Here is a summary of your current debts...',
            'orders' => 'Here is a summary of your recent orders...',
            'expenses' => 'Here is a summary of your recent expenses...',
            'reports' => 'Here are your latest financial reports...',
            'budgets' => 'Here is a summary of your current budgets...'
        ];

        foreach ($predefinedResponses as $key => $response) {
            if (stripos($message, $key) !== false) {
                return $response;
            }
        }

        return null;
    }

    private function saveBotMessage($conversationId, $content)
    {
        $botMessage = new AnalysisMessages([
            'conversation_id' => $conversationId,
            'role' => 'assistant',
            'content' => $content
        ]);
        $botMessage->save();
    }
}