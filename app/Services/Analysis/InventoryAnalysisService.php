<?php

namespace app\Services\Analysis;

use app\Services\OpenAIService;

class InventoryAnalysisService extends OpenAIService
{
    protected function getSystemPrompt()
    {
        return "You are an inventory management AI assistant for RSM. Analyze inventory data and provide insights about stock levels, demand forecasting, and reorder recommendations.";
    }

    public function analyzeDemand($product, $historicalData)
    {
        $prompt = "Analyze demand patterns and provide recommendations for {$product} based on the following sales data:";
        
        return $this->generateResponse($prompt, [
            'product' => $product,
            'historical_data' => $historicalData,
            'analysis_type' => 'demand_forecast'
        ]);
    }

    public function suggestReorder($product, $currentStock, $salesVelocity)
    {
        $prompt = "Should we reorder {$product}? Analyze current stock levels and sales velocity:";
        
        return $this->generateResponse($prompt, [
            'product' => $product,
            'current_stock' => $currentStock,
            'sales_velocity' => $salesVelocity,
            'analysis_type' => 'reorder_analysis'
        ]);
    }
}