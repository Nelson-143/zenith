<?php

namespace app\Services\Analysis;

use app\Services\OpenAIService;

class FinancialAnalysisService extends OpenAIService
{
    protected function getSystemPrompt()
    {
        return "You are a financial analysis AI assistant for RSM. Analyze financial data and provide insights about profitability, cash flow, and business performance.";
    }

    public function analyzeProfitability($timeframe, $financialData)
    {
        $prompt = "Analyze profitability trends over {$timeframe} and provide key insights:";
        
        return $this->generateResponse($prompt, [
            'timeframe' => $timeframe,
            'financial_data' => $financialData,
            'analysis_type' => 'profitability_analysis'
        ]);
    }

    public function assessLoanViability($loanAmount, $financialMetrics)
    {
        $prompt = "Evaluate loan viability for {$loanAmount} based on current financial metrics:";
        
        return $this->generateResponse($prompt, [
            'loan_amount' => $loanAmount,
            'financial_metrics' => $financialMetrics,
            'analysis_type' => 'loan_assessment'
        ]);
    }
}