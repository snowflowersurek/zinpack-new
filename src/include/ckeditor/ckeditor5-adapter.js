/**
 * CKEditor 5 Adapter for CKEditor 4 API Compatibility
 * This adapter provides backward compatibility for existing CKEditor 4 code
 */

// CKEditor 4 호환성을 위한 CKEDITOR 객체 생성
window.CKEDITOR = window.CKEDITOR || {};

// instances 객체 생성
CKEDITOR.instances = {};

// tools 객체 생성 (업로드 콜백용)
CKEDITOR.tools = {
    callFunction: function(funcNum, url, message) {
        if (window.parent && window.parent.postMessage) {
            window.parent.postMessage({
                type: 'ckeditor_upload',
                funcNum: funcNum,
                url: url,
                message: message
            }, '*');
        }
    }
};

// CKEditor 5 인스턴스를 관리하는 매니저
class CKEditor5Manager {
    constructor() {
        this.editors = new Map();
    }

    async createClassicEditor(element, config = {}) {
        try {
            // CKEditor 5 기본 설정
            const defaultConfig = {
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'underline', '|',
                    'link', 'bulletedList', 'numberedList', '|',
                    'outdent', 'indent', '|',
                    'imageUpload', 'blockQuote', 'insertTable', '|',
                    'undo', 'redo'
                ],
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                    ]
                },
                image: {
                    toolbar: ['imageStyle:inline', 'imageStyle:block', 'imageStyle:side', '|', 'toggleImageCaption', 'imageTextAlternative']
                },
                table: {
                    contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
                }
            };

            // 설정 병합
            const finalConfig = { ...defaultConfig, ...config };

            // CKEditor 5 인스턴스 생성
            const editor = await ClassicEditor.create(element, finalConfig);
            
            // 인스턴스 저장
            const elementId = element.id || `editor_${Date.now()}`;
            this.editors.set(elementId, editor);
            
            // CKEditor 4 호환성을 위한 래퍼 객체 생성
            const compatibilityWrapper = {
                editor: editor,
                
                // CKEditor 4 호환 메서드들
                getData: function() {
                    return editor.getData();
                },
                
                setData: function(data) {
                    return editor.setData(data);
                },
                
                focus: function() {
                    return editor.focus();
                },
                
                destroy: function() {
                    return editor.destroy();
                },
                
                // CKEditor 5 특화 메서드들도 제공
                getEditor: function() {
                    return editor;
                }
            };
            
            // CKEDITOR.instances에 등록
            CKEDITOR.instances[elementId] = compatibilityWrapper;
            
            return compatibilityWrapper;
            
        } catch (error) {
            console.error('CKEditor 5 초기화 실패:', error);
            throw error;
        }
    }

    destroyEditor(elementId) {
        const wrapper = CKEDITOR.instances[elementId];
        if (wrapper && wrapper.editor) {
            wrapper.editor.destroy();
            delete CKEDITOR.instances[elementId];
            this.editors.delete(elementId);
        }
    }

    destroyAll() {
        for (const [elementId] of this.editors) {
            this.destroyEditor(elementId);
        }
    }
}

// 전역 매니저 인스턴스
window.ckeditor5Manager = new CKEditor5Manager();

// CKEditor 4 스타일의 초기화 함수
window.initializeCKEditor = function(selector, config = {}) {
    // DOM이 로드된 후 실행
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initializeEditors(selector, config);
        });
    } else {
        initializeEditors(selector, config);
    }
};

function initializeEditors(selector, config) {
    const elements = typeof selector === 'string' ? 
        document.querySelectorAll(selector) : 
        [selector];
    
    elements.forEach(async (element) => {
        if (element && element.tagName === 'TEXTAREA') {
            try {
                await window.ckeditor5Manager.createClassicEditor(element, config);
            } catch (error) {
                console.error('에디터 초기화 중 오류:', error);
            }
        }
    });
}

// 자동 초기화 (기존 CKEditor 4 호환성)
document.addEventListener('DOMContentLoaded', function() {
    // class="ckeditor"가 있는 모든 textarea 자동 초기화
    const textareas = document.querySelectorAll('textarea.ckeditor');
    textareas.forEach(async (textarea) => {
        try {
            await window.ckeditor5Manager.createClassicEditor(textarea);
        } catch (error) {
            console.error('자동 초기화 중 오류:', error);
        }
    });
});

// 페이지 언로드 시 정리
window.addEventListener('beforeunload', function() {
    window.ckeditor5Manager.destroyAll();
}); 