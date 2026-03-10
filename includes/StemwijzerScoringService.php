<?php

class StemwijzerScoringService
{
    private array $validAnswers = ['eens', 'neutraal', 'oneens'];

    public function calculate(array $questions, array $answers, array $weights = [], int $minimumAnswered = 8): array
    {
        $partyStats = [];
        $answeredByUser = 0;

        foreach ($questions as $index => $question) {
            $userAnswer = $answers[(string)$index] ?? $answers[$index] ?? null;
            if ($userAnswer === 'geen_mening' || $userAnswer === 'overslaan' || !in_array($userAnswer, $this->validAnswers, true)) {
                continue;
            }

            $positions = is_object($question) ? ($question->positions ?? []) : ($question['positions'] ?? []);
            if (!is_array($positions)) {
                continue;
            }

            $answeredByUser++;
            $weight = max(1.0, (float)($weights[(string)$index] ?? $weights[$index] ?? 1));

            foreach ($positions as $party => $partyAnswer) {
                if (!in_array($partyAnswer, $this->validAnswers, true)) {
                    continue; // partij zonder standpunt telt niet mee
                }

                if (!isset($partyStats[$party])) {
                    $partyStats[$party] = [
                        'weighted_score' => 0.0,
                        'weighted_total' => 0.0,
                        'matched' => 0,
                        'partial' => 0,
                        'opposed' => 0,
                        'considered' => 0,
                        'missing_positions' => 0
                    ];
                }

                $match = $this->matchValue($userAnswer, $partyAnswer);
                $partyStats[$party]['weighted_score'] += $match * $weight;
                $partyStats[$party]['weighted_total'] += $weight;
                $partyStats[$party]['considered']++;

                if ($match === 1.0) {
                    $partyStats[$party]['matched']++;
                } elseif ($match === 0.5) {
                    $partyStats[$party]['partial']++;
                } else {
                    $partyStats[$party]['opposed']++;
                }
            }
        }

        $results = [];
        foreach ($partyStats as $party => $stat) {
            if ($stat['weighted_total'] <= 0) {
                continue;
            }

            $score = round(($stat['weighted_score'] / $stat['weighted_total']) * 100);
            $results[] = [
                'name' => $party,
                'agreement' => $score,
                'score' => round($stat['weighted_score'], 4),
                'total' => round($stat['weighted_total'], 4),
                'matched' => $stat['matched'],
                'partial' => $stat['partial'],
                'opposed' => $stat['opposed'],
                'considered' => $stat['considered']
            ];
        }

        usort($results, function ($a, $b) {
            if ($a['agreement'] === $b['agreement']) {
                return strcmp($a['name'], $b['name']);
            }
            return $b['agreement'] <=> $a['agreement'];
        });

        $rank = 1;
        $prevScore = null;
        foreach ($results as $i => &$row) {
            if ($prevScore !== null && $row['agreement'] !== $prevScore) {
                $rank = $i + 1;
            }
            $row['rank'] = $rank;
            $prevScore = $row['agreement'];
        }

        return [
            'results' => $results,
            'meta' => [
                'minimum_answered' => $minimumAnswered,
                'answered_by_user' => $answeredByUser,
                'is_reliable' => $answeredByUser >= $minimumAnswered,
                'methodology' => [
                    'version' => 'v2',
                    'answers' => ['eens', 'neutraal', 'oneens', 'geen_mening'],
                    'rules' => [
                        'exact_match' => 1.0,
                        'neutral_vs_expressed' => 0.5,
                        'opposite' => 0.0,
                        'skip_excluded' => true,
                        'missing_party_position_excluded' => true,
                        'weighting_enabled' => true
                    ]
                ]
            ]
        ];
    }

    private function matchValue(string $userAnswer, string $partyAnswer): float
    {
        if ($userAnswer === $partyAnswer) {
            return 1.0;
        }

        if ($userAnswer === 'neutraal' || $partyAnswer === 'neutraal') {
            return 0.5;
        }

        return 0.0;
    }
}
