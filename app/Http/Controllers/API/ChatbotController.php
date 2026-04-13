<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    public function bootstrap(): JsonResponse
    {
        $config = config('chatbot');

        return response()->json([
            'status' => 'success',
            'data' => [
                'assistant_name' => $config['assistant_name'],
                'assistant_badge' => $config['assistant_badge'],
                'disclaimer' => $config['disclaimer'],
                'quick_replies' => $config['quick_replies'],
                'welcome_message' => "Hello! I am {$config['assistant_name']}. Ask me about events, OTP, payments, QR check-in, pledgers, or support.",
                'mode' => 'rule_based',
                'is_ai' => true,
            ],
        ]);
    }

    public function message(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|min:2|max:500',
        ]);

        $config = config('chatbot');
        $message = $this->normalize($validated['message']);
        $knowledgeBase = $config['knowledge_base'] ?? [];
        $scored = [];

        foreach ($knowledgeBase as $entry) {
            $score = $this->scoreMessage($message, $entry['keywords'] ?? []);
            if ($score > 0) {
                $scored[] = [
                    'score' => $score,
                    'entry' => $entry,
                ];
            }
        }

        usort($scored, fn ($a, $b) => $b['score'] <=> $a['score']);

        if (empty($scored)) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'answer' => $config['fallback']['answer'],
                    'confidence' => 'low',
                    'matched_topic' => 'fallback',
                    'follow_ups' => $config['fallback']['follow_ups'],
                    'is_ai' => true,
                    'mode' => 'rule_based',
                    'disclaimer' => $config['disclaimer'],
                ],
            ]);
        }

        $best = $scored[0];
        $bestScore = $best['score'];
        $confidence = $bestScore >= 8 ? 'high' : ($bestScore >= 5 ? 'medium' : 'low');

        $suggestions = collect(array_slice($scored, 0, 3))
            ->map(fn ($item) => $item['entry']['title'] ?? null)
            ->filter()
            ->values()
            ->all();

        return response()->json([
            'status' => 'success',
            'data' => [
                'answer' => $best['entry']['answer'],
                'confidence' => $confidence,
                'matched_topic' => $best['entry']['id'] ?? 'unknown',
                'follow_ups' => $best['entry']['follow_ups'] ?? [],
                'related_topics' => $suggestions,
                'is_ai' => true,
                'mode' => 'rule_based',
                'disclaimer' => $config['disclaimer'],
            ],
        ]);
    }

    private function normalize(string $text): string
    {
        $text = Str::lower($text);
        $text = preg_replace('/[^a-z0-9\s]/', ' ', $text) ?? '';
        $text = preg_replace('/\s+/', ' ', $text) ?? '';

        return trim($text);
    }

    private function scoreMessage(string $message, array $keywords): int
    {
        if ($message === '' || empty($keywords)) {
            return 0;
        }

        $score = 0;
        $tokens = array_filter(explode(' ', $message));

        foreach ($keywords as $rawKeyword) {
            $keyword = $this->normalize((string) $rawKeyword);
            if ($keyword === '') {
                continue;
            }

            if (str_contains($message, $keyword)) {
                $score += 5;
                continue;
            }

            $keywordTokens = array_filter(explode(' ', $keyword));
            $common = count(array_intersect($tokens, $keywordTokens));
            if ($common > 0) {
                $score += $common;
            }
        }

        return $score;
    }
}
