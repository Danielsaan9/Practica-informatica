// Helper to resolve ESM default/named exports from CommonJS require()
const resolve = (mod, name) => mod.default || mod[name] || mod;

// Core - keep full module for Editor class and utilities
const TiptapCore = require('@tiptap/core');

// Extensions - resolve to actual extension class
const TiptapStarterKit = resolve(require('@tiptap/starter-kit'), 'StarterKit');
const TiptapImage = resolve(require('@tiptap/extension-image'), 'Image');
const TiptapPlaceholder = resolve(require('@tiptap/extension-placeholder'), 'Placeholder');
const TiptapSuggestion = resolve(require('@tiptap/suggestion'), 'Suggestion');
const TiptapBubbleMenu = resolve(require('@tiptap/extension-bubble-menu'), 'BubbleMenu');

// Table extensions
const TableKit = resolve(require('@tiptap/extension-table'), 'TableKit');

// File handler for drag & drop / paste image uploads
const TiptapFileHandler = resolve(require('@tiptap/extension-file-handler'), 'FileHandler');

// ProseMirror primitives for table grips (decorations, cell selection, structural edits)
const TiptapPMState = require('@tiptap/pm/state');
const TiptapPMView = require('@tiptap/pm/view');
const TiptapPMTables = require('@tiptap/pm/tables');

// Floating UI for popups (slash commands menu)
const FloatingUI = require('@floating-ui/dom');

// Expose to global scope
window.TiptapCore = TiptapCore;
window.TiptapStarterKit = TiptapStarterKit;
window.TiptapImage = TiptapImage;
window.TiptapPlaceholder = TiptapPlaceholder;
window.TiptapSuggestion = TiptapSuggestion;
window.TiptapBubbleMenu = TiptapBubbleMenu;
window.TableKit = TableKit;
window.TiptapFileHandler = TiptapFileHandler;
window.TiptapPMState = TiptapPMState;
window.TiptapPMView = TiptapPMView;
window.TiptapPMTables = TiptapPMTables;
window.FloatingUI = FloatingUI;
