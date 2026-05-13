<?php

return [
    'provider' => getenv('AI_PROVIDER') ?: 'openai',
    'api_key' => getenv('AI_API_KEY') ?: '',
    'api_endpoint' => getenv('AI_API_ENDPOINT') ?: '',
    'model' => getenv('AI_MODEL') ?: 'gpt-3.5-turbo',
    'daily_limit' => (int)(getenv('AI_DAILY_LIMIT') ?: 100),
];
