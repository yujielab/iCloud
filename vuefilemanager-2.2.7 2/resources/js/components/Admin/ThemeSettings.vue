<template>
    <div class="theme-settings">
        <div class="theme-settings-header">
            <h2>主题设置</h2>
            <p>自定义应用程序的外观和感觉</p>
        </div>

        <div class="settings-section">
            <h3>颜色</h3>
            <div class="color-grid">
                <div v-for="(color, name) in colors" :key="name" class="color-item">
                    <label>{{ name }}</label>
                    <div class="color-picker">
                        <input type="color" 
                               :value="color" 
                               @input="updateColor(name, $event.target.value)"
                               class="color-input">
                        <span class="color-value">{{ color }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="settings-section">
            <h3>动画效果</h3>
            <div class="animation-settings">
                <div class="setting-item">
                    <label>
                        <input type="checkbox" 
                               v-model="animations.enabled"
                               @change="updateAnimations">
                        启用动画效果
                    </label>
                </div>
                <div class="setting-item" v-if="animations.enabled">
                    <label>动画速度</label>
                    <select v-model="animations.speed" @change="updateAnimations">
                        <option value="fast">快速</option>
                        <option value="normal">正常</option>
                        <option value="slow">慢速</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="settings-section">
            <h3>圆角</h3>
            <div class="radius-settings">
                <div class="setting-item">
                    <label>边角圆润度</label>
                    <input type="range" 
                           v-model="borderRadius" 
                           min="0" 
                           max="20" 
                           @input="updateBorderRadius">
                    <span>{{ borderRadius }}px</span>
                </div>
            </div>
        </div>

        <div class="settings-section">
            <h3>模糊效果</h3>
            <div class="blur-settings">
                <div class="setting-item">
                    <label>
                        <input type="checkbox" 
                               v-model="blur.enabled"
                               @change="updateBlur">
                        启用模糊效果
                    </label>
                </div>
                <div class="setting-item" v-if="blur.enabled">
                    <label>模糊强度</label>
                    <input type="range" 
                           v-model="blur.intensity" 
                           min="0" 
                           max="20"
                           @input="updateBlur">
                    <span>{{ blur.intensity }}px</span>
                </div>
            </div>
        </div>

        <div class="settings-section">
            <h3>预设主题</h3>
            <div class="preset-themes">
                <button 
                    v-for="theme in presetThemes" 
                    :key="theme.name"
                    @click="applyPresetTheme(theme)"
                    class="preset-theme-btn">
                    {{ theme.name }}
                </button>
            </div>
        </div>

        <div class="settings-section">
            <h3>深色模式</h3>
            <div class="dark-mode-settings">
                <div class="setting-item">
                    <label>
                        <input type="checkbox" 
                               v-model="darkMode.enabled"
                               @change="updateDarkMode">
                        启用深色模式
                    </label>
                </div>
                <div class="setting-item" v-if="darkMode.enabled">
                    <label>自动切换</label>
                    <select v-model="darkMode.auto" @change="updateDarkMode">
                        <option value="system">跟随系统</option>
                        <option value="time">按时间</option>
                        <option value="manual">手动</option>
                    </select>
                </div>
                <div class="setting-item" v-if="darkMode.enabled && darkMode.auto === 'time'">
                    <label>深色模式时间</label>
                    <div class="time-range">
                        <input type="time" v-model="darkMode.startTime" @change="updateDarkMode">
                        <span>至</span>
                        <input type="time" v-model="darkMode.endTime" @change="updateDarkMode">
                    </div>
                </div>
            </div>
        </div>

        <div class="settings-section">
            <h3>主题预览</h3>
            <div class="theme-preview">
                <div class="preview-card" :class="{ 'dark-mode': isDarkMode }">
                    <div class="preview-header">
                        <h4>预览标题</h4>
                        <p>这是一段预览文本，展示当前主题的效果。</p>
                    </div>
                    <div class="preview-content">
                        <button class="preview-button primary">主要按钮</button>
                        <button class="preview-button secondary">次要按钮</button>
                        <input type="text" class="preview-input" placeholder="输入框预览">
                    </div>
                    <div class="preview-footer">
                        <div class="preview-tag success">成功</div>
                        <div class="preview-tag warning">警告</div>
                        <div class="preview-tag danger">危险</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="settings-actions">
            <button @click="saveSettings" class="save-btn">保存设置</button>
            <button @click="resetDefaults" class="reset-btn">恢复默认</button>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ThemeSettings',
    data() {
        return {
            colors: {
                primary: '#007AFF',
                secondary: '#5856D6',
                success: '#34C759',
                warning: '#FF9500',
                danger: '#FF3B30',
                gray1: '#8E8E93',
                gray2: '#AEAEB2',
                gray3: '#C7C7CC',
                gray4: '#D1D1D6',
                gray5: '#E5E5EA',
                gray6: '#F2F2F7'
            },
            darkColors: {
                primary: '#0A84FF',
                secondary: '#5E5CE6',
                success: '#34C759',
                warning: '#FF9500',
                danger: '#FF3B30',
                gray1: '#8E8E93',
                gray2: '#AEAEB2',
                gray3: '#C7C7CC',
                gray4: '#D1D1D6',
                gray5: '#E5E5EA',
                gray6: '#F2F2F7'
            },
            animations: {
                enabled: true,
                speed: 'normal'
            },
            borderRadius: 8,
            blur: {
                enabled: true,
                intensity: 8
            },
            presetThemes: [
                { name: 'Light', theme: 'light' },
                { name: 'Dark', theme: 'dark' },
                { name: 'Classic', theme: 'classic' }
            ],
            darkMode: {
                enabled: true,
                auto: 'system',
                startTime: '20:00',
                endTime: '06:00'
            },
            defaultSettings: {
                colors: {
                    primary: '#007AFF',
                    secondary: '#5856D6',
                    success: '#34C759',
                    warning: '#FF9500',
                    danger: '#FF3B30',
                    gray1: '#8E8E93',
                    gray2: '#AEAEB2',
                    gray3: '#C7C7CC',
                    gray4: '#D1D1D6',
                    gray5: '#E5E5EA',
                    gray6: '#F2F2F7'
                },
                darkColors: {
                    primary: '#0A84FF',
                    secondary: '#5E5CE6',
                    success: '#34C759',
                    warning: '#FF9500',
                    danger: '#FF3B30',
                    gray1: '#8E8E93',
                    gray2: '#AEAEB2',
                    gray3: '#C7C7CC',
                    gray4: '#D1D1D6',
                    gray5: '#E5E5EA',
                    gray6: '#F2F2F7'
                },
                animations: {
                    enabled: true,
                    speed: 'normal'
                },
                borderRadius: 8,
                blur: {
                    enabled: true,
                    intensity: 8
                },
                darkMode: {
                    enabled: true,
                    auto: 'system',
                    startTime: '20:00',
                    endTime: '06:00'
                }
            }
        }
    },
    computed: {
        isDarkMode() {
            if (!this.darkMode.enabled) return false;
            
            if (this.darkMode.auto === 'system') {
                return window.matchMedia('(prefers-color-scheme: dark)').matches;
            }
            
            if (this.darkMode.auto === 'time') {
                const now = new Date();
                const currentTime = now.getHours() * 60 + now.getMinutes();
                const startTime = this.timeToMinutes(this.darkMode.startTime);
                const endTime = this.timeToMinutes(this.darkMode.endTime);
                
                if (startTime > endTime) {
                    return currentTime >= startTime || currentTime < endTime;
                }
                return currentTime >= startTime && currentTime < endTime;
            }
            
            return true;
        }
    },
    created() {
        this.loadSettings()
    },
    methods: {
        updateColor(name, value) {
            this.colors[name] = value
            this.updateTheme()
        },
        updateAnimations() {
            this.updateTheme()
        },
        updateBorderRadius() {
            this.updateTheme()
        },
        updateBlur() {
            this.updateTheme()
        },
        updateTheme() {
            const root = document.documentElement;
            const isDark = this.isDarkMode;
            
            // 更新颜色
            Object.entries(this.colors).forEach(([name, value]) => {
                const darkValue = this.darkColors?.[name] || value;
                root.style.setProperty(
                    `--apple-${name}`,
                    isDark ? darkValue : value
                );
            });
            
            // 更新动画
            const transitionSpeed = {
                fast: '0.2s',
                normal: '0.3s',
                slow: '0.45s'
            }
            root.style.setProperty('--apple-transition-base', 
                this.animations.enabled ? transitionSpeed[this.animations.speed] : '0s')
            
            // 更新圆角
            root.style.setProperty('--apple-border-radius-md', `${this.borderRadius}px`)
            
            // 更新模糊效果
            root.style.setProperty('--apple-blur-md', 
                this.blur.enabled ? `blur(${this.blur.intensity}px)` : 'none')
        },
        async applyPresetTheme(theme) {
            try {
                const response = await axios.get(`/api/admin/theme-settings/preset/${theme.theme}`);
                const presetData = response.data;
                
                if (!this.validateSettings(presetData)) {
                    throw new Error('预设主题数据无效');
                }
                
                // 应用预设主题设置
                this.colors = presetData.colors;
                this.darkColors = presetData.darkColors || this.defaultSettings.darkColors;
                this.darkMode = {
                    ...this.darkMode,
                    ...presetData.darkMode
                };
                
                // 保持其他设置不变，除非预设主题特别指定
                if (presetData.animations) this.animations = presetData.animations;
                if (presetData.borderRadius) this.borderRadius = presetData.borderRadius;
                if (presetData.blur) this.blur = presetData.blur;
                
                this.updateTheme();
                
                this.$notify({
                    type: 'success',
                    message: `已应用${theme.name}主题`
                });
            } catch (error) {
                console.error('应用预设主题失败:', error);
                this.$notify({
                    type: 'error',
                    message: error.message || '应用预设主题失败'
                });
            }
        },
        async saveSettings() {
            try {
                const settings = {
                    colors: this.colors,
                    darkColors: this.darkColors,
                    animations: this.animations,
                    borderRadius: this.borderRadius,
                    blur: this.blur,
                    darkMode: this.darkMode
                };

                // 数据验证
                if (!this.validateSettings(settings)) {
                    throw new Error('设置数据无效');
                }

                // 重试机制
                let retries = 3;
                while (retries > 0) {
                    try {
                        await axios.post('/api/admin/theme-settings', settings);
                        this.$notify({
                            type: 'success',
                            message: '主题设置已保存'
                        });
                        break;
                    } catch (error) {
                        retries--;
                        if (retries === 0) throw error;
                        await new Promise(resolve => setTimeout(resolve, 1000));
                    }
                }
            } catch (error) {
                console.error('保存设置失败:', error);
                this.$notify({
                    type: 'error',
                    message: error.message || '保存设置失败'
                });
            }
        },
        validateSettings(settings) {
            // 验证颜色格式
            const isValidColor = (color) => /^#[0-9A-Fa-f]{6}$/.test(color);
            
            // 验证所有颜色值
            for (const [key, value] of Object.entries(settings.colors)) {
                if (!isValidColor(value)) return false;
            }
            
            // 验证动画设置
            if (typeof settings.animations.enabled !== 'boolean') return false;
            if (!['fast', 'normal', 'slow'].includes(settings.animations.speed)) return false;
            
            // 验证圆角设置
            if (typeof settings.borderRadius !== 'number' || 
                settings.borderRadius < 0 || 
                settings.borderRadius > 20) return false;
            
            // 验证模糊设置
            if (typeof settings.blur.enabled !== 'boolean') return false;
            if (typeof settings.blur.intensity !== 'number' || 
                settings.blur.intensity < 0 || 
                settings.blur.intensity > 20) return false;
            
            return true;
        },
        async loadSettings() {
            try {
                const response = await axios.get('/api/admin/theme-settings');
                const settings = response.data;
                
                // 验证获取的设置
                if (!this.validateSettings(settings)) {
                    throw new Error('获取的设置数据无效');
                }
                
                // 应用设置
                this.colors = settings.colors || this.defaultSettings.colors;
                this.darkColors = settings.darkColors || this.defaultSettings.darkColors;
                this.animations = settings.animations || this.defaultSettings.animations;
                this.borderRadius = settings.borderRadius ?? this.defaultSettings.borderRadius;
                this.blur = settings.blur || this.defaultSettings.blur;
                this.darkMode = settings.darkMode || this.defaultSettings.darkMode;
                
                this.updateTheme();
            } catch (error) {
                console.error('加载主题设置失败:', error);
                // 使用默认设置
                this.resetDefaults();
                this.$notify({
                    type: 'warning',
                    message: '加载设置失败，已使用默认设置'
                });
            }
        },
        resetDefaults() {
            this.colors = {
                primary: '#007AFF',
                secondary: '#5856D6',
                success: '#34C759',
                warning: '#FF9500',
                danger: '#FF3B30',
                gray1: '#8E8E93',
                gray2: '#AEAEB2',
                gray3: '#C7C7CC',
                gray4: '#D1D1D6',
                gray5: '#E5E5EA',
                gray6: '#F2F2F7'
            }
            this.darkColors = {
                primary: '#0A84FF',
                secondary: '#5E5CE6',
                success: '#34C759',
                warning: '#FF9500',
                danger: '#FF3B30',
                gray1: '#8E8E93',
                gray2: '#AEAEB2',
                gray3: '#C7C7CC',
                gray4: '#D1D1D6',
                gray5: '#E5E5EA',
                gray6: '#F2F2F7'
            }
            this.animations = {
                enabled: true,
                speed: 'normal'
            }
            this.borderRadius = 8
            this.blur = {
                enabled: true,
                intensity: 8
            }
            this.darkMode = {
                enabled: true,
                auto: 'system',
                startTime: '20:00',
                endTime: '06:00'
            }
            this.updateTheme()
        },
        timeToMinutes(time) {
            const [hours, minutes] = time.split(':').map(Number);
            return hours * 60 + minutes;
        },
        updateDarkMode() {
            document.documentElement.classList.remove('light-mode', 'dark-mode');
            document.documentElement.classList.add(this.isDarkMode ? 'dark-mode' : 'light-mode');
            this.updateTheme();
        }
    },
    watch: {
        isDarkMode: {
            immediate: true,
            handler(newValue) {
                this.updateDarkMode();
            }
        }
    },
    mounted() {
        // 监听系统深色模式变化
        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: dark)')
                .addEventListener('change', this.updateDarkMode);
        }
    },
    beforeDestroy() {
        // 清理监听器
        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: dark)')
                .removeEventListener('change', this.updateDarkMode);
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/sass/_apple-theme.scss';

.theme-settings {
    @include apple-card;
    padding: var(--apple-spacing-lg);
    max-width: 800px;
    margin: 0 auto;
    
    .theme-settings-header {
        margin-bottom: var(--apple-spacing-xl);
        
        h2 {
            font-family: var(--apple-font-family);
            font-size: 24px;
            margin-bottom: var(--apple-spacing-xs);
        }
        
        p {
            color: var(--apple-gray1);
        }
    }
    
    .settings-section {
        margin-bottom: var(--apple-spacing-xl);
        
        h3 {
            font-family: var(--apple-font-family);
            font-size: 18px;
            margin-bottom: var(--apple-spacing-md);
        }
    }
    
    .color-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: var(--apple-spacing-md);
        
        .color-item {
            label {
                display: block;
                margin-bottom: var(--apple-spacing-xs);
            }
            
            .color-picker {
                display: flex;
                align-items: center;
                gap: var(--apple-spacing-sm);
                
                .color-input {
                    width: 40px;
                    height: 40px;
                    border: none;
                    border-radius: var(--apple-border-radius-sm);
                    cursor: pointer;
                }
                
                .color-value {
                    font-family: monospace;
                }
            }
        }
    }
    
    .setting-item {
        margin-bottom: var(--apple-spacing-md);
        
        label {
            display: block;
            margin-bottom: var(--apple-spacing-xs);
        }
        
        input[type="range"] {
            width: 100%;
            max-width: 300px;
        }
        
        select {
            @include apple-input;
            width: 200px;
        }
    }
    
    .preset-themes {
        display: flex;
        gap: var(--apple-spacing-md);
        
        .preset-theme-btn {
            @include apple-button;
            background: var(--apple-gray6);
            color: var(--apple-primary);
            border: none;
            cursor: pointer;
            
            &:hover {
                background: var(--apple-gray5);
            }
        }
    }
    
    .settings-actions {
        display: flex;
        gap: var(--apple-spacing-md);
        margin-top: var(--apple-spacing-xl);
        
        button {
            @include apple-button;
            
            &.save-btn {
                background: var(--apple-primary);
                color: white;
                border: none;
            }
            
            &.reset-btn {
                background: var(--apple-gray6);
                color: var(--apple-danger);
                border: none;
            }
        }
    }
}

.dark-mode-settings {
    .time-range {
        display: flex;
        align-items: center;
        gap: var(--apple-spacing-md);
        
        input[type="time"] {
            @include apple-input;
            width: 120px;
        }
    }
}

.theme-preview {
    .preview-card {
        @include apple-card;
        padding: var(--apple-spacing-lg);
        
        &.dark-mode {
            background: var(--apple-dark-background);
            color: white;
        }
        
        .preview-header {
            margin-bottom: var(--apple-spacing-lg);
            
            h4 {
                font-size: var(--apple-font-size-lg);
                margin-bottom: var(--apple-spacing-xs);
            }
            
            p {
                color: var(--apple-gray1);
            }
        }
        
        .preview-content {
            display: flex;
            gap: var(--apple-spacing-md);
            margin-bottom: var(--apple-spacing-lg);
            
            .preview-button {
                @include apple-button;
                
                &.primary {
                    background: var(--apple-primary);
                    color: white;
                }
                
                &.secondary {
                    background: var(--apple-gray6);
                    color: var(--apple-primary);
                }
            }
            
            .preview-input {
                @include apple-input;
            }
        }
        
        .preview-footer {
            display: flex;
            gap: var(--apple-spacing-sm);
            
            .preview-tag {
                padding: 4px 8px;
                border-radius: var(--apple-border-radius-sm);
                font-size: var(--apple-font-size-sm);
                
                &.success {
                    background: var(--apple-success);
                    color: white;
                }
                
                &.warning {
                    background: var(--apple-warning);
                    color: white;
                }
                
                &.danger {
                    background: var(--apple-danger);
                    color: white;
                }
            }
        }
    }
}
</style> 