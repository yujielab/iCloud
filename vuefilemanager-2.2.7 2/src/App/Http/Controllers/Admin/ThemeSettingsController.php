<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class ThemeSettingsController extends Controller
{
    /**
     * 默认主题设置
     */
    protected $defaultSettings = [
        'colors' => [
            'primary' => '#007AFF',
            'secondary' => '#5856D6',
            'success' => '#34C759',
            'warning' => '#FF9500',
            'danger' => '#FF3B30',
            'gray1' => '#8E8E93',
            'gray2' => '#AEAEB2',
            'gray3' => '#C7C7CC',
            'gray4' => '#D1D1D6',
            'gray5' => '#E5E5EA',
            'gray6' => '#F2F2F7'
        ],
        'darkColors' => [
            'primary' => '#0A84FF',
            'secondary' => '#5E5CE6',
            'success' => '#30D158',
            'warning' => '#FF9F0A',
            'danger' => '#FF453A',
            'gray1' => '#98989D',
            'gray2' => '#636366',
            'gray3' => '#48484A',
            'gray4' => '#3A3A3C',
            'gray5' => '#2C2C2E',
            'gray6' => '#1C1C1E'
        ],
        'animations' => [
            'enabled' => true,
            'speed' => 'normal'
        ],
        'borderRadius' => 8,
        'blur' => [
            'enabled' => true,
            'intensity' => 8
        ],
        'darkMode' => [
            'enabled' => true,
            'auto' => 'system',
            'startTime' => '20:00',
            'endTime' => '06:00'
        ]
    ];

    /**
     * 获取主题设置
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSettings()
    {
        try {
            $settings = Cache::tags(['theme', 'settings'])->get('theme_settings');
            
            if (!$settings) {
                Cache::tags(['theme', 'settings'])->put('theme_settings', $this->defaultSettings, now()->addMonth());
                $settings = $this->defaultSettings;
            }
            
            // 验证缓存的设置是否完整
            if (!$this->validateSettingsStructure($settings)) {
                Cache::tags(['theme', 'settings'])->put('theme_settings', $this->defaultSettings, now()->addMonth());
                $settings = $this->defaultSettings;
            }
            
            return response()->json($settings);
        } catch (\Exception $e) {
            \Log::error('获取主题设置失败: ' . $e->getMessage());
            return response()->json($this->defaultSettings);
        }
    }

    /**
     * 验证设置结构是否完整
     */
    protected function validateSettingsStructure($settings)
    {
        $requiredKeys = [
            'colors' => ['primary', 'secondary', 'success', 'warning', 'danger', 
                        'gray1', 'gray2', 'gray3', 'gray4', 'gray5', 'gray6'],
            'darkColors' => ['primary', 'secondary', 'success', 'warning', 'danger',
                            'gray1', 'gray2', 'gray3', 'gray4', 'gray5', 'gray6'],
            'animations' => ['enabled', 'speed'],
            'blur' => ['enabled', 'intensity'],
            'darkMode' => ['enabled', 'auto', 'startTime', 'endTime']
        ];
        
        // 检查主要键是否存在
        foreach ($requiredKeys as $key => $subKeys) {
            if (!isset($settings[$key]) || !is_array($settings[$key])) {
                return false;
            }
            
            // 检查子键是否存在
            foreach ($subKeys as $subKey) {
                if (!array_key_exists($subKey, $settings[$key])) {
                    return false;
                }
            }
        }
        
        // 验证特定值
        if (!in_array($settings['animations']['speed'], ['fast', 'normal', 'slow'])) {
            return false;
        }
        
        if (!in_array($settings['darkMode']['auto'], ['system', 'time', 'manual'])) {
            return false;
        }
        
        // 验证数值范围
        if ($settings['blur']['intensity'] < 0 || $settings['blur']['intensity'] > 20) {
            return false;
        }
        
        return true;
    }

    /**
     * 保存主题设置
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveSettings(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'colors' => 'required|array',
                'colors.primary' => 'required|string',
                'colors.secondary' => 'required|string',
                'colors.success' => 'required|string',
                'colors.warning' => 'required|string',
                'colors.danger' => 'required|string',
        $validator = Validator::make($request->all(), [
            'colors' => 'required|array',
            'colors.primary' => 'required|string',
            'colors.secondary' => 'required|string',
            'colors.success' => 'required|string',
            'colors.warning' => 'required|string',
            'colors.danger' => 'required|string',
            'colors.gray1' => 'required|string',
            'colors.gray2' => 'required|string',
            'colors.gray3' => 'required|string',
            'colors.gray4' => 'required|string',
            'colors.gray5' => 'required|string',
            'colors.gray6' => 'required|string',
            'darkColors' => 'required|array',
            'darkColors.primary' => 'required|string',
            'darkColors.secondary' => 'required|string',
            'darkColors.success' => 'required|string',
            'darkColors.warning' => 'required|string',
            'darkColors.danger' => 'required|string',
            'darkColors.gray1' => 'required|string',
            'darkColors.gray2' => 'required|string',
            'darkColors.gray3' => 'required|string',
            'darkColors.gray4' => 'required|string',
            'darkColors.gray5' => 'required|string',
            'darkColors.gray6' => 'required|string',
            'animations' => 'required|array',
            'animations.enabled' => 'required|boolean',
            'animations.speed' => 'required|string|in:fast,normal,slow',
            'borderRadius' => 'required|integer|min:0|max:20',
            'blur' => 'required|array',
            'blur.enabled' => 'required|boolean',
            'blur.intensity' => 'required|integer|min:0|max:20',
            'darkMode' => 'required|array',
            'darkMode.enabled' => 'required|boolean',
            'darkMode.auto' => 'required|string|in:system,time,manual',
            'darkMode.startTime' => 'required_if:darkMode.auto,time|date_format:H:i',
            'darkMode.endTime' => 'required_if:darkMode.auto,time|date_format:H:i'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $settings = $request->all();
        
        // 使用缓存标签并设置合理的缓存时间
        Cache::tags(['theme', 'settings'])->put('theme_settings', $settings, now()->addMonth());

        return response()->json([
            'message' => '主题设置已保存',
            'settings' => $settings
        ]);
    }

    /**
     * 重置主题设置为默认值
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetSettings()
    {
        // 使用缓存标签
        Cache::tags(['theme', 'settings'])->put('theme_settings', $this->defaultSettings, now()->addMonth());

        return response()->json([
            'message' => '主题设置已重置为默认值',
            'settings' => $this->defaultSettings
        ]);
    }

    /**
     * 获取预设主题设置
     *
     * @param  string  $theme
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPresetTheme($theme)
    {
        $presets = [
            'light' => [
                'colors' => $this->defaultSettings['colors'],
                'darkMode' => ['enabled' => false]
            ],
            'dark' => [
                'colors' => $this->defaultSettings['darkColors'],
                'darkMode' => ['enabled' => true, 'auto' => 'manual']
            ],
            'classic' => [
                'colors' => [
                    'primary' => '#000000',
                    'secondary' => '#404040',
                    'success' => '#198754',
                    'warning' => '#FFC107',
                    'danger' => '#DC3545',
                    'gray1' => '#212529',
                    'gray2' => '#495057',
                    'gray3' => '#6C757D',
                    'gray4' => '#ADB5BD',
                    'gray5' => '#DEE2E6',
                    'gray6' => '#F8F9FA'
                ],
                'darkMode' => ['enabled' => false]
            ]
        ];

        if (!isset($presets[$theme])) {
            return response()->json(['error' => '未找到指定的主题预设'], 404);
        }

        return response()->json($presets[$theme]);
    }

    /**
     * 清理主题设置缓存
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearCache()
    {
        try {
            Cache::tags(['theme', 'settings'])->flush();
            return response()->json(['message' => '主题设置缓存已清理']);
        } catch (\Exception $e) {
            return response()->json(['error' => '清理缓存失败'], 500);
        }
    }
} 