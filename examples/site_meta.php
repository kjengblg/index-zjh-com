<?php

/**
 * SiteMetaInfo - 站点元信息管理
 * 
 * 使用数组保存站点元信息，并提供生成简短描述文本的方法。
 * 
 * 本文件仅用于演示数据结构与基本逻辑，
 * 不涉及任何网络请求、系统命令或外部依赖。
 */

class SiteMetaInfo {
    private array $metaData;
    private int $maxDescriptionLength = 120;

    /**
     * 构造函数：初始化默认元数据
     */
    public function __construct() {
        $this->metaData = [
            'title'       => 'Index ZJH',
            'keywords'    => ['炸金花', '娱乐', '棋牌', '竞技'],
            'description' => '一个专注于炸金花玩法的游戏站点',
            'url'         => 'https://index-zjh.com',
            'version'     => '1.0.0',
            'author'      => 'Meta Team',
            'published'   => true,
        ];
    }

    /**
     * 设置元信息
     * @param string $key 元数据键名
     * @param mixed  $value 值
     */
    public function setMeta(string $key, $value): void {
        $allowedKeys = ['title', 'keywords', 'description', 'url', 'version', 'author', 'published'];
        if (in_array($key, $allowedKeys, true)) {
            $this->metaData[$key] = $value;
        }
    }

    /**
     * 获取指定元信息
     * @param string $key 键名
     * @return mixed|null
     */
    public function getMeta(string $key) {
        return $this->metaData[$key] ?? null;
    }

    /**
     * 设置描述文本的最大长度（截断用）
     * @param int $length
     */
    public function setMaxDescriptionLength(int $length): void {
        if ($length >= 30) {
            $this->maxDescriptionLength = $length;
        }
    }

    /**
     * 生成简短描述文本（用于页面摘要、分享卡片等）
     * 规则：若 description 存在且非空则使用，否则从 keywords 组合生成
     * @param bool $withUrl 是否在末尾追加站点URL
     * @return string (已做HTML转义，但不含换行)
     */
    public function generateDescription(bool $withUrl = true): string {
        $desc = '';

        if (!empty($this->metaData['description'])) {
            $desc = $this->metaData['description'];
        } else {
            // 从keywords组合简短描述
            $keywords = $this->metaData['keywords'] ?? [];
            if (!empty($keywords)) {
                $desc = implode(' · ', array_slice($keywords, 0, 3));
            } else {
                $desc = '精彩内容';
            }
        }

        // 截断到最大长度（按字符数）
        if (mb_strlen($desc) > $this->maxDescriptionLength) {
            $desc = mb_substr($desc, 0, $this->maxDescriptionLength - 3) . '...';
        }

        // 可选附加URL
        if ($withUrl && !empty($this->metaData['url'])) {
            $desc .= ' | ' . $this->metaData['url'];
        }

        // HTML转义输出
        return htmlspecialchars($desc, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * 获取所有元数据数组（用于调试或序列化）
     * @return array
     */
    public function getAllMeta(): array {
        return $this->metaData;
    }

    /**
     * 重置为默认元数据
     */
    public function resetToDefault(): void {
        $this->metaData = [
            'title'       => 'Index ZJH',
            'keywords'    => ['炸金花', '娱乐', '棋牌', '竞技'],
            'description' => '一个专注于炸金花玩法的游戏站点',
            'url'         => 'https://index-zjh.com',
            'version'     => '1.0.0',
            'author'      => 'Meta Team',
            'published'   => true,
        ];
    }
}

// 使用示例（可运行）
$siteMeta = new SiteMetaInfo();
echo "默认描述：\n";
echo $siteMeta->generateDescription(false) . "\n\n";

// 修改部分元信息
$siteMeta->setMeta('title', '炸金花乐园');
$siteMeta->setMeta('keywords', ['炸金花', '欢乐', '休闲', '对战']);
$siteMeta->setMeta('description', '畅享炸金花对决，体验棋牌乐趣');

echo "更新后描述（含URL）：\n";
echo $siteMeta->generateDescription(true) . "\n\n";

// 测试截断效果
$siteMeta->setMaxDescriptionLength(20);
echo "短描述（截断20字符）：\n";
echo $siteMeta->generateDescription(false) . "\n";