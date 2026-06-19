<?php
/**
 * 站点元信息配置与描述生成工具
 * 
 * 本文件用于定义站点的元数据数组，并提供生成简短描述文本的方法。
 * 适用于 SEO、页面标题、社交分享等场景。
 */

// 站点元信息数组，包含基本配置、关键词、URL等
$siteMeta = [
    'site_name' => '乐鱼体育',
    'site_url' => 'https://official-ssl-leyu.com.cn',
    'description' => '乐鱼体育 - 专业的体育资讯与赛事分析平台',
    'keywords' => ['乐鱼体育', '体育资讯', '赛事分析', '运动数据'],
    'author' => '乐鱼体育团队',
    'language' => 'zh-CN',
    'charset' => 'UTF-8',
    'version' => '1.2.0',
    'created_at' => '2024-01-15',
    'updated_at' => '2025-03-28',
    'social_links' => [
        'twitter' => 'https://twitter.com/leyusports',
        'facebook' => 'https://facebook.com/leyusports',
        'weibo' => 'https://weibo.com/leyusports'
    ],
    'contact' => [
        'email' => 'support@official-ssl-leyu.com.cn',
        'phone' => '+86-400-123-4567'
    ],
    'features' => [
        'live_scores' => true,
        'news_feed' => true,
        'video_highlights' => true,
        'user_comments' => false
    ],
    'seo' => [
        'meta_title' => '乐鱼体育 - 最新体育动态与深度分析',
        'meta_description' => '乐鱼体育提供全面的体育新闻、赛事直播、数据统计和专家预测，覆盖足球、篮球、网球等多个项目。',
        'og_image' => 'https://official-ssl-leyu.com.cn/images/og-default.jpg',
        'og_type' => 'website'
    ]
];

/**
 * 生成用于页面摘要的简短描述文本
 *
 * @param array $meta 站点元信息数组
 * @param int $maxLength 描述文本最大长度，默认150字符
 * @return string 处理后的描述文本
 */
function generateShortDescription(array $meta, int $maxLength = 150): string
{
    // 优先使用 SEO 专用描述，否则使用通用描述
    $baseDescription = $meta['seo']['meta_description'] ?? $meta['description'] ?? '';

    // 如果为空，使用站点名称和关键词组合
    if (empty(trim($baseDescription))) {
        $siteName = $meta['site_name'] ?? '未知站点';
        $keywords = isset($meta['keywords']) && is_array($meta['keywords'])
            ? implode('、', $meta['keywords'])
            : '';
        $baseDescription = $siteName . ' - ' . $keywords;
    }

    // 去除多余空白
    $baseDescription = preg_replace('/\s+/', ' ', $baseDescription);
    $baseDescription = trim($baseDescription);

    // 截取到指定长度，避免截断在单词中间
    if (mb_strlen($baseDescription) > $maxLength) {
        $baseDescription = mb_substr($baseDescription, 0, $maxLength - 3) . '...';
    }

    // 对输出进行 HTML 转义，防止 XSS
    return htmlspecialchars($baseDescription, ENT_QUOTES | ENT_HTML5, $meta['charset'] ?? 'UTF-8');
}

/**
 * 获取站点元信息的某个字段值，支持嵌套键（如 'seo.meta_title'）
 *
 * @param array $meta 站点元信息数组
 * @param string $key 点分隔的键路径
 * @param mixed $default 默认值
 * @return mixed 字段值或默认值
 */
function getSiteMetaField(array $meta, string $key, $default = null)
{
    $keys = explode('.', $key);
    $current = $meta;
    foreach ($keys as $segment) {
        if (!is_array($current) || !array_key_exists($segment, $current)) {
            return $default;
        }
        $current = $current[$segment];
    }
    return $current;
}

// 示例：生成并使用描述文本
$description = generateShortDescription($siteMeta);

// 输出示例（实际使用中可赋值给模板变量）
// echo $description;

// 以下为简单测试（仅用于演示，不包含外部依赖）
if (php_sapi_name() === 'cli') {
    echo "生成的描述文本：\n";
    echo $description . "\n\n";
    echo "站点名称：" . getSiteMetaField($siteMeta, 'site_name', '未知') . "\n";
    echo "站点URL：" . getSiteMetaField($siteMeta, 'site_url', '#') . "\n";
    echo "关键词：" . implode(', ', $siteMeta['keywords']) . "\n";
}