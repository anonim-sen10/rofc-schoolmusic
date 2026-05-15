@extends('portal.layout')

@section('title', $moduleTitle.' | ROFC')
@section('page-title', $moduleTitle)

@section('content')
<style>
    /* Premium Table Hover & Micro-interactions */
    .table-wrap table tbody tr {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .table-wrap table tbody tr:hover {
        background-color: #f8fbff !important;
        /* Removed transform to prevent breaking fixed-position modals */
    }
    
    /* Fix for fixed-position popovers being clipped by transformed rows */
    .table-wrap table tbody tr:has(details.action-popover[open]),
    .table-wrap table tbody tr:hover:has(details.action-popover[open]) {
        transform: none !important;
        z-index: 100 !important;
        position: relative;
    }

    .table-wrap:has(details.action-popover[open]),
    .portal-content:has(details.action-popover[open]),
    .portal-main:has(details.action-popover[open]) {
        transform: none !important;
        overflow: visible !important;
    }

    .premium-create-card-btn {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        padding: 1.25rem 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        font-weight: 700;
        color: #1e293b;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        width: fit-content;
        margin-bottom: 2rem;
        text-decoration: none;
        font-family: inherit;
        font-size: 1rem;
        border-style: solid;
    }

    .premium-create-card-btn:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-color: #6366f1;
        color: #6366f1;
    }

    .premium-create-card-btn i {
        color: #6366f1;
        width: 1.5rem;
        height: 1.5rem;
    }
    
    /* Schedule Tooltip Style */
    .schedule-tooltip-wrap {
        position: relative;
        display: inline-block;
        color: #6366f1;
        font-weight: 600;
        border-bottom: 1px dashed #c7d2fe;
        cursor: help;
        transition: all 0.2s;
    }
    .schedule-tooltip-wrap:hover {
        color: #4338ca;
        border-bottom-color: #4338ca;
    }
    
    .schedule-tooltip {
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%) translateY(0);
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(12px);
        border: 1px solid #e2e8f0;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        padding: 14px;
        border-radius: 14px;
        z-index: 1000;
        width: 220px;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        pointer-events: none;
    }
    
    .schedule-tooltip::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -6px;
        border-width: 6px;
        border-style: solid;
        border-color: #fff transparent transparent transparent;
    }
    
    .schedule-tooltip-wrap:hover .schedule-tooltip {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(-12px);
    }
    
    .tooltip-header {
        font-size: 0.7rem;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .tooltip-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 4px;
        font-size: 0.8rem;
    }
    .tooltip-day { font-weight: 700; color: #1e293b; }
    .tooltip-time { color: #64748b; font-variant-numeric: tabular-nums; }

    /* Premium Modal System */
    .premium-modal-wrapper {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        opacity: 0;
        visibility: hidden;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .premium-modal-wrapper.active {
        opacity: 1;
        visibility: visible;
    }

    .premium-modal-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, 0.5);
        backdrop-filter: blur(10px);
        transition: opacity 0.4s ease;
    }

    .premium-modal-content {
        background: #ffffff;
        width: 100%;
        max-width: 900px;
        border-radius: 2rem;
        position: relative;
        z-index: 10;
        box-shadow: 
            0 25px 50px -12px rgba(0, 0, 0, 0.25),
            0 0 0 1px rgba(255, 255, 255, 0.1) inset;
        display: flex;
        flex-direction: column;
        max-height: 90vh;
        transform: scale(0.95) translateY(20px);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        overflow: hidden;
    }

    .premium-modal-wrapper.active .premium-modal-content {
        transform: scale(1) translateY(0);
    }

    /* ─── GLOBAL FIRM MODAL SYSTEM (REPLACES POPOVERS) ─── */
    details.action-popover {
        position: static;
        display: inline-block;
    }

    /* Force reset all potential parent transforms that break 'position: fixed' */
    .table-wrap:has(details.action-popover[open]),
    .portal-content:has(details.action-popover[open]),
    .portal-main:has(details.action-popover[open]),
    .portal-shell:has(details.action-popover[open]),
    table:has(details.action-popover[open]),
    tbody:has(details.action-popover[open]),
    tr:has(details.action-popover[open]),
    td:has(details.action-popover[open]) {
        transform: none !important;
        perspective: none !important;
        filter: none !important;
        contain: none !important;
        backdrop-filter: none !important;
        /* Ensure the modal is visible */
        overflow: visible !important;
    }

    details.action-popover[open]::before {
        content: '';
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 100000;
        cursor: pointer;
    }

    details.action-popover[open] .action-popover-form {
        position: fixed !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        width: 90vw !important;
        max-width: 800px !important;
        height: auto !important;
        max-height: 85vh !important;
        background: #ffffff !important;
        border-radius: 2rem !important;
        z-index: 100001 !important;
        display: flex !important;
        flex-direction: column !important;
        box-shadow: 0 40px 120px rgba(0, 0, 0, 0.5) !important;
        animation: modalFadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        overflow: hidden !important;
        border: 1px solid rgba(0, 0, 0, 0.1) !important;
        margin: 0 !important;
    }

    @keyframes modalFadeIn {
        from { opacity: 0; transform: translate(-50%, -45%) scale(0.95); }
        to { opacity: 1; transform: translate(-50%, -50%) scale(1); }
    }

    .registration-modal-header {
        padding: 1.25rem 2rem;
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .registration-modal-header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .registration-modal-icon {
        width: 3rem;
        height: 3rem;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-radius: 0.85rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        box-shadow: 0 8px 16px rgba(99, 102, 241, 0.15);
    }

    .registration-modal-header h3 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.02em;
    }

    .registration-modal-header p {
        margin: 0.15rem 0 0 0;
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 500;
    }

    .registration-modal-close-btn {
        width: 2.25rem;
        height: 2.25rem;
        border-radius: 0.65rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .registration-modal-close-btn:hover {
        background: #fee2e2;
        color: #ef4444;
        border-color: #fecaca;
        transform: rotate(90deg);
    }

    .registration-modal-body {
        padding: 1.75rem 2rem;
        overflow-y: auto;
        flex: 1;
        background: #ffffff;
    }

    .registration-modal-summary {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.15rem 1.5rem;
        background: #f8fafc;
        border-radius: 1.25rem;
        margin-bottom: 1.5rem;
        border: 1px solid #f1f5f9;
    }

    .registration-modal-summary-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .registration-modal-avatar {
        width: 3rem;
        height: 3rem;
        background: #6366f1;
        color: #fff;
        border-radius: 0.85rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        font-weight: 800;
    }

    .registration-modal-summary-name {
        font-size: 1rem;
        font-weight: 800;
        color: #1e293b;
        margin: 0;
    }

    .registration-modal-summary-left p:first-child {
        font-size: 0.7rem;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.15rem;
    }

    .registration-modal-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }

    @media (max-width: 1024px) {
        .registration-modal-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .registration-modal-grid {
            grid-template-columns: 1fr;
        }
    }

    .registration-modal-grid article {
        background: #ffffff;
        padding: 1rem;
        border-radius: 1rem;
        border: 1px solid #f1f5f9;
        transition: all 0.2s;
    }

    .registration-modal-grid article:hover {
        border-color: #6366f1;
        background: #fcfdff;
        transform: translateY(-1px);
    }

    .registration-modal-grid article p:first-child {
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        color: #94a3b8;
        margin-bottom: 0.4rem;
        letter-spacing: 0.05em;
    }

    .registration-modal-grid article p:last-child {
        font-size: 0.9rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        line-height: 1.4;
        word-break: break-word;
    }

    .registration-modal-item-full {
        grid-column: 1 / -1;
    }

    .registration-modal-footer {
        padding: 1.25rem 2rem;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    .registration-modal-btn {
        padding: 0.65rem 1.5rem;
        border-radius: 0.85rem;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .registration-modal-btn-primary {
        background: #6366f1;
        color: #fff !important;
        box-shadow: 0 10px 15px rgba(99, 102, 241, 0.2);
    }

    .registration-modal-btn-secondary {
        background: #ffffff;
        border: 1px solid #e2e8f0 !important;
        color: #64748b !important;
    }

    .registration-modal-btn-danger {
        background: #ef4444;
        color: #fff !important;
        box-shadow: 0 10px 15px rgba(239, 68, 68, 0.2);
    }

    .premium-modal-header {
        padding: 1.75rem 2.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #f1f5f9;
        background: #ffffff;
        position: sticky;
        top: 0;
        z-index: 20;
    }

    .premium-modal-header-info {
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }

    .premium-modal-icon {
        width: 3.5rem;
        height: 3.5rem;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2);
    }

    .premium-modal-header-info h3 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.01em;
    }

    .premium-modal-header-info p {
        margin: 0.25rem 0 0 0;
        font-size: 0.9rem;
        color: #64748b;
        font-weight: 500;
    }

    .premium-modal-close {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.75rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .premium-modal-close:hover {
        background: #fee2e2;
        color: #ef4444;
        border-color: #fecaca;
        transform: rotate(90deg);
    }

    .premium-modal-body {
        padding: 2.5rem;
        overflow-y: auto;
        background: #ffffff;
        flex: 1;
        min-height: 0; /* Important for flex child scrolling */
    }

    #form-create-teacher-modal {
        display: flex;
        flex-direction: column;
        flex: 1;
        overflow: hidden;
    }

    .premium-modal-footer {
        padding: 1.5rem 2.5rem;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 1rem;
        border-top: 1px solid #f1f5f9;
        background: #f8fafc;
        position: sticky;
        bottom: 0;
        z-index: 20;
    }

    /* Form Layout */
    .premium-form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .premium-field {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .premium-field.full-width {
        grid-column: span 2;
    }

    .premium-field label {
        font-size: 0.85rem;
        font-weight: 700;
        color: #475569;
        padding-left: 0.25rem;
    }

    .premium-input, .premium-select, .premium-textarea {
        width: 100%;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 0.85rem;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        font-weight: 500;
        color: #1e293b;
        transition: all 0.2s;
    }

    .premium-input:focus, .premium-select:focus, .premium-textarea:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        background: #ffffff;
    }

    .premium-input:hover, .premium-select:hover, .premium-textarea:hover {
        border-color: #cbd5e1;
    }

    .premium-textarea {
        min-height: 100px;
        resize: vertical;
    }

    /* Avatar Preview */
    .avatar-upload-container {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        background: #f8fafc;
        padding: 1.25rem;
        border-radius: 1.25rem;
        border: 1px dashed #e2e8f0;
        margin-bottom: 0.5rem;
    }

    .avatar-preview-box {
        width: 5rem;
        height: 5rem;
        border-radius: 1rem;
        background: #e2e8f0;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 2px solid #ffffff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .avatar-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-preview-box i {
        color: #94a3b8;
        width: 2rem;
        height: 2rem;
    }

    .upload-info h4 {
        margin: 0;
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
    }

    .upload-info p {
        margin: 0.25rem 0 0.75rem 0;
        font-size: 0.8rem;
        color: #64748b;
    }

    /* Buttons */
    .btn-premium-primary {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #ffffff;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 1rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 8px 15px rgba(99, 102, 241, 0.2);
    }

    .btn-premium-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 20px rgba(99, 102, 241, 0.3);
        filter: brightness(1.05);
    }

    .btn-premium-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .btn-premium-secondary {
        background: #ffffff;
        color: #64748b;
        border: 1px solid #e2e8f0;
        padding: 0.75rem 2rem;
        border-radius: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-premium-secondary:hover {
        background: #f8fafc;
        color: #1e293b;
        border-color: #cbd5e1;
    }

    @media (max-width: 768px) {
        .premium-modal-content {
            max-height: 100vh;
            border-radius: 0;
        }
        .premium-form-grid {
            grid-template-columns: 1fr;
        }
        .premium-field.full-width {
            grid-column: span 1;
        }
        .premium-modal-body {
            padding: 1.5rem;
        }
    }

    /* Student Creation Modal Styles (Global) */
    .premium-form-card {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        background: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(12px);
    }

    .premium-form-container {
        background: #ffffff;
        border: 1px solid rgba(255, 255, 255, 1);
        border-radius: 2.5rem;
        width: 100%;
        max-width: 950px;
        max-height: 90vh;
        overflow-y: auto;
        padding: 3rem;
        position: relative;
        box-shadow: 
            0 25px 50px -12px rgba(0, 0, 0, 0.1),
            0 0 0 1px rgba(0, 0, 0, 0.05);
        animation: modalSlideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes modalSlideUp {
        from { opacity: 0; transform: translateY(30px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .premium-form-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 3rem;
        position: relative;
    }

    .premium-form-icon {
        width: 4.5rem;
        height: 4.5rem;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-radius: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        box-shadow: 0 12px 24px rgba(99, 102, 241, 0.3);
        flex-shrink: 0;
    }

    .premium-form-title h2 {
        margin: 0;
        font-size: 1.75rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.025em;
    }

    .premium-form-title p {
        margin: 0.35rem 0 0 0;
        color: #64748b;
        font-size: 1rem;
        font-weight: 500;
    }

    .premium-modal-close {
        position: absolute;
        top: 2rem;
        right: 2rem;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #64748b;
        width: 2.75rem;
        height: 2.75rem;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 10;
    }

    .premium-modal-close:hover {
        background: #fee2e2;
        color: #ef4444;
        border-color: #fecaca;
        transform: rotate(90deg);
    }

    .premium-form-group {
        margin-bottom: 3rem;
        background: #f8fafc;
        padding: 2rem;
        border-radius: 2rem;
        border: 1px solid #f1f5f9;
        transition: all 0.3s ease;
    }

    .premium-form-group:hover {
        background: #ffffff;
        border-color: #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
    }

    .premium-form-group-title {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        margin-bottom: 2rem;
        padding-bottom: 1.25rem;
        border-bottom: 2px solid #ffffff;
    }

    .premium-form-group-title span {
        width: 2rem;
        height: 2rem;
        background: #6366f1;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 0.9rem;
        font-weight: 800;
        box-shadow: 0 4px 10px rgba(99, 102, 241, 0.2);
    }

    .premium-form-group-title h3 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
    }

    .premium-form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.75rem;
    }

    .premium-form-grid .full-width {
        grid-column: span 2;
    }

    .premium-field {
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
    }

    .premium-field label {
        font-size: 0.9rem;
        font-weight: 700;
        color: #475569;
        margin-left: 0.25rem;
    }

    .premium-input, .premium-select, .premium-textarea {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 1rem;
        padding: 0.9rem 1.25rem;
        color: #0f172a;
        font-family: inherit;
        font-size: 1rem;
        font-weight: 500;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .premium-input::placeholder {
        color: #94a3b8;
    }

    .premium-input:hover, .premium-select:hover, .premium-textarea:hover {
        border-color: #cbd5e1;
    }

    .premium-input:focus, .premium-select:focus, .premium-textarea:focus {
        outline: none;
        border-color: #6366f1;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .premium-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1.25rem;
        padding-right: 3rem;
    }

    .premium-textarea {
        resize: vertical;
        min-height: 120px;
    }

    .premium-form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1.25rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #f1f5f9;
    }

    .btn-premium-primary {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #ffffff;
        border: none;
        padding: 1rem 3rem;
        border-radius: 1.25rem;
        font-weight: 700;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 10px 25px rgba(99, 102, 241, 0.25);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .btn-premium-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(99, 102, 241, 0.35);
        filter: brightness(1.05);
    }

    .btn-premium-primary:active {
        transform: translateY(-1px);
    }

    .btn-premium-secondary {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        padding: 1rem 3rem;
        border-radius: 1.25rem;
        font-weight: 700;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-premium-secondary:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .student-list-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .student-list-header h3 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
    }

    .btn-add-student {
        background: #ffffff;
        color: #4f46e5;
        padding: 0.85rem 1.75rem;
        border-radius: 1.15rem;
        font-weight: 700;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1.5px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .btn-add-student:hover {
        border-color: #6366f1;
        background: #f5f3ff;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(99, 102, 241, 0.15);
    }

    /* Toast Notifications */
    .premium-toast {
        position: fixed;
        top: 2rem;
        right: 2rem;
        z-index: 10000;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        pointer-events: none;
    }

    .toast-item {
        background: #ffffff;
        border-radius: 1rem;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        border: 1px solid #f1f5f9;
        pointer-events: auto;
        animation: toastSlideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        max-width: 350px;
    }

    @keyframes toastSlideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    .toast-item.success { border-left: 4px solid #10b981; }
    .toast-item.error { border-left: 4px solid #ef4444; }

    .toast-icon {
        width: 2rem;
        height: 2rem;
        border-radius: 0.6rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .success .toast-icon { background: #ecfdf5; color: #10b981; }
    .error .toast-icon { background: #fef2f2; color: #ef4444; }

    .toast-content h4 { margin: 0; font-size: 0.95rem; font-weight: 700; color: #1e293b; }
    .toast-content p { margin: 0.15rem 0 0 0; font-size: 0.85rem; color: #64748b; }

    @media (max-width: 768px) {
        .premium-modal-content {
            max-height: 100vh;
            border-radius: 0;
        }
        .premium-form-grid {
            grid-template-columns: 1fr;
        }
        .premium-field.full-width {
            grid-column: span 1;
        }
        .premium-modal-body {
            padding: 1.5rem;
        }
    }
</style>

<div class="premium-toast" id="toast-container"></div>

@if ($moduleKey === 'users' || $moduleKey === 'roles')
    <section class="card" id="form-create-user" style="display: none;" data-searchable>
        <h3>Buat Akun Login Baru</h3>
        <form class="module-form module-form-grid" method="POST" action="{{ route('super-admin.users.store') }}">
            @csrf
            <label>Nama
                <input type="text" name="name" value="{{ old('name') }}" required>
            </label>
            <label>Email
                <input type="email" name="email" value="{{ old('email') }}" required>
            </label>
            <label>Role
                <select name="role" required>
                    <option value="super_admin" @selected(old('role') === 'super_admin')>Super Admin</option>
                    <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                    <option value="finance" @selected(old('role') === 'finance')>Finance</option>
                    <option value="teacher" @selected(old('role') === 'teacher')>Teacher</option>
                    <option value="student" @selected(old('role') === 'student')>Siswa</option>
                </select>
            </label>
            <label>Instrument (khusus teacher)
                <input type="text" name="instrument" value="{{ old('instrument') }}" placeholder="Drum, Piano, Guitar, dll">
            </label>
            <label>No. Telepon (khusus siswa)
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxx">
            </label>
            <label>Password
                <input type="password" name="password" required>
            </label>
            <label>Konfirmasi Password
                <input type="password" name="password_confirmation" required>
            </label>
            <div class="form-actions">
                <button type="submit">Buat Akun</button>
                <button type="reset" class="btn-secondary">Cancel</button>
            </div>
        </form>
    </section>
@endif

@if ($moduleKey === 'users' || $moduleKey === 'roles')
    <section class="card" data-searchable>
        <div class="card-header-flex">
            <h3>Data User</h3>
            <button type="button" class="btn-add-student" onclick="const form = document.getElementById('form-create-user'); if(form) form.style.display = form.style.display === 'none' ? 'block' : 'none';">
                <i data-lucide="user-plus"></i>
                Buat Akun Login Baru
            </button>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($usersForRoles as $userRow)
                        <tr>
                            <td data-label="Nama">{{ $userRow->name }}</td>
                            <td data-label="Email">{{ $userRow->email }}</td>
                            <td data-label="Role">{{ $userRow->roles->pluck('slug')->implode(', ') }}</td>
                            <td data-label="Created">{{ optional($userRow->created_at)->format('Y-m-d H:i') }}</td>
                            <td data-label="Aksi">
                                <div class="action-icons">
                                    {{-- Detail Button --}}
                                    <details class="action-popover registration-style-popover">
                                        <summary class="btn-icon" title="Detail" aria-label="Detail"><i data-lucide="eye"></i></summary>
                                        <div class="action-popover-form registration-edit-form">
                                            <header class="registration-modal-header">
                                                <div class="registration-modal-header-left">
                                                    <span class="registration-modal-icon">
                                                        <i data-lucide="clipboard-list"></i>
                                                    </span>
                                                    <div>
                                                        <h3>Detail User</h3>
                                                        <p>Informasi lengkap akun pengguna</p>
                                                    </div>
                                                </div>
                                                <button type="button" class="registration-modal-close-btn" aria-label="Tutup" onclick="this.closest('details').removeAttribute('open');"><i data-lucide="x"></i></button>
                                            </header>
                                            <div class="registration-modal-body">
                                                <div class="registration-modal-summary">
                                                    <div class="registration-modal-summary-left">
                                                        <div class="registration-modal-avatar">
                                                            {{ strtoupper(substr($userRow->name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <p>Nama User</p>
                                                            <p class="registration-modal-summary-name">{{ $userRow->name }}</p>
                                                        </div>
                                                    </div>
                                                    <x-ui.badge type="neutral">
                                                        {{ strtoupper($userRow->roles->pluck('slug')->first() ?? 'USER') }}
                                                    </x-ui.badge>
                                                </div>
                                                <section class="registration-modal-grid">
                                                    <article class="registration-modal-item-full">
                                                        <p>Email Address</p>
                                                        <p>{{ $userRow->email }}</p>
                                                    </article>
                                                    <article>
                                                        <p>Role Akses</p>
                                                        <p>{{ $userRow->roles->pluck('name')->join(', ') ?: '-' }}</p>
                                                    </article>
                                                    <article>
                                                        <p>Terdaftar Sejak</p>
                                                        <p>{{ optional($userRow->created_at)->format('d M Y H:i') }}</p>
                                                    </article>
                                                    <article>
                                                        <p>Verifikasi Email</p>
                                                        <p>{{ $userRow->email_verified_at ? 'Terverifikasi' : 'Belum Verifikasi' }}</p>
                                                    </article>
                                                    <article>
                                                        <p>ID Akun</p>
                                                        <p>#{{ $userRow->id }}</p>
                                                    </article>
                                                </section>
                                            </div>
                                            <footer class="registration-modal-footer">
                                                <button type="button" class="registration-modal-btn registration-modal-btn-secondary action-popover-close"><i data-lucide="x" class="w-4 h-4"></i> Tutup</button>
                                                <a href="{{ route('super-admin.users.impersonate', $userRow->id) }}" class="registration-modal-btn" style="background: #0f172a !important; color: #fff !important; border: none !important;">
                                                    <i data-lucide="user-plus" class="w-4 h-4"></i> Login As
                                                </a>
                                                <button type="button" class="registration-modal-btn registration-modal-btn-primary" onclick="this.closest('.action-icons').querySelector('details:nth-child(2)').setAttribute('open', 'true'); this.closest('details').removeAttribute('open');"><i data-lucide="user-cog" class="w-4 h-4"></i> Edit Akses</button>
                                                <button type="button" class="registration-modal-btn registration-modal-btn-danger" onclick="if(confirm('Hapus user ini?')) this.closest('.action-icons').querySelector('form.delete-form').submit();"><i data-lucide="trash-2" class="w-4 h-4"></i> Hapus</button>
                                            </footer>
                                        </div>
                                    </details>
                                    <details class="action-popover registration-style-popover">
                                        <summary class="btn-icon" title="Edit" aria-label="Edit"><i data-lucide="pencil-line"></i></summary>
                                        <form class="action-popover-form registration-edit-form" method="POST" action="{{ route('super-admin.users.update', $userRow) }}">
                                            @csrf
                                            @method('PUT')
                                            <header class="registration-modal-header">
                                                <div class="registration-modal-header-left">
                                                    <span class="registration-modal-icon">
                                                        <i data-lucide="pencil-line"></i>
                                                    </span>
                                                    <div>
                                                        <h3>Edit User</h3>
                                                        <p>Perbarui informasi akun dan akses</p>
                                                    </div>
                                                </div>
                                                <button type="button" class="registration-modal-close-btn" aria-label="Tutup" onclick="this.closest('details').removeAttribute('open');"><i data-lucide="x"></i></button>
                                            </header>
                                            <div class="registration-modal-body">
                                                <div class="module-form-grid">
                                                    <label>Nama
                                                        <input type="text" name="name" value="{{ $userRow->name }}" required>
                                                    </label>
                                                    <label>Email
                                                        <input type="email" name="email" value="{{ $userRow->email }}" required>
                                                    </label>
                                                    <label>Role
                                                        <select name="role" required>
                                                            <option value="super_admin" @selected($userRow->hasRole('super_admin'))>Super Admin</option>
                                                            <option value="admin" @selected($userRow->hasRole('admin'))>Admin</option>
                                                            <option value="finance" @selected($userRow->hasRole('finance'))>Finance</option>
                                                            <option value="teacher" @selected($userRow->hasRole('teacher'))>Teacher</option>
                                                            <option value="student" @selected($userRow->hasRole('student'))>Siswa</option>
                                                        </select>
                                                    </label>
                                                    <label>Password Baru (opsional)
                                                        <input type="password" name="password" placeholder="Kosongkan jika tidak diganti">
                                                    </label>
                                                </div>
                                            </div>
                                            <footer class="registration-modal-footer">
                                                <button type="button" class="registration-modal-btn registration-modal-btn-secondary action-popover-close"><i data-lucide="x"></i> Batal</button>
                                                <button type="submit" class="registration-modal-btn registration-modal-btn-primary"><i data-lucide="check"></i> Simpan Perubahan</button>
                                            </footer>
                                        </form>
                                    </details>
                                    <form class="delete-form" method="POST" action="{{ route('super-admin.users.destroy', $userRow) }}" onsubmit="return confirm('Hapus user ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon btn-icon-danger" title="Hapus" aria-label="Hapus"><i data-lucide="trash-2"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No user records yet. Create your first account to get started.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endif

@if ($moduleKey === 'teachers')
    
    {{-- Teacher Creation Modal --}}
    <div id="modal-create-teacher" class="premium-modal-wrapper @if($errors->any() && old('_form_type') === 'create_teacher') active @endif">
        <div class="premium-modal-backdrop" onclick="closeTeacherModal()"></div>
        <div class="premium-modal-content">
            <header class="premium-modal-header">
                <div class="premium-modal-header-info">
                    <div class="premium-modal-icon">
                        <i data-lucide="music-2"></i>
                    </div>
                    <div>
                        <h3>Tambah Teacher Baru</h3>
                        <p>Lengkapi data pengajar profesional ROFC</p>
                    </div>
                </div>
                <button type="button" class="premium-modal-close" onclick="closeTeacherModal()">
                    <i data-lucide="x"></i>
                </button>
            </header>

            <form id="form-create-teacher-modal" action="{{ route('super-admin.teachers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_form_type" value="create_teacher">
                
                <div class="premium-modal-body">
                    <div class="premium-form-grid">
                        {{-- Profil Photo Section --}}
                        <div class="premium-field full-width">
                            <label>Foto Profil</label>
                            <div class="avatar-upload-container">
                                <div class="avatar-preview-box" id="avatar-preview">
                                    <i data-lucide="user"></i>
                                </div>
                                <div class="upload-info">
                                    <h4>Pilih Foto Terbaik</h4>
                                    <p>Format JPG, PNG atau WebP (Max 2MB)</p>
                                    <input type="file" name="photo" id="teacher-photo-input" accept="image/*" class="premium-input" style="padding: 0.5rem;" onchange="previewImage(this, 'avatar-preview')">
                                </div>
                            </div>
                        </div>

                        <div class="premium-field">
                            <label for="teacher-name">Nama Lengkap</label>
                            <input type="text" id="teacher-name" name="name" class="premium-input" value="{{ old('name') }}" placeholder="Contoh: John Doe, M.Mus" required>
                        </div>

                        <div class="premium-field">
                            <label for="teacher-email">Email Address</label>
                            <input type="email" id="teacher-email" name="email" class="premium-input" value="{{ old('email') }}" placeholder="nama@email.com" required>
                        </div>

                        <div class="premium-field">
                            <label for="teacher-phone">Nomor HP / WhatsApp</label>
                            <input type="text" id="teacher-phone" name="phone" class="premium-input" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" required>
                        </div>

                        <div class="premium-field">
                            <label for="teacher-gender">Jenis Kelamin</label>
                            <select id="teacher-gender" name="gender" class="premium-select" required>
                                <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                <option value="laki-laki" @selected(old('gender') === 'laki-laki')>Laki-laki</option>
                                <option value="perempuan" @selected(old('gender') === 'perempuan')>Perempuan</option>
                            </select>
                        </div>

                        <div class="premium-field">
                            <label for="teacher-religion">Agama</label>
                            <select id="teacher-religion" name="religion" class="premium-select" required>
                                <option value="" disabled selected>Pilih Agama</option>
                                <option value="Islam" @selected(old('religion') === 'Islam')>Islam</option>
                                <option value="Kristen" @selected(old('religion') === 'Kristen')>Kristen</option>
                                <option value="Katolik" @selected(old('religion') === 'Katolik')>Katolik</option>
                                <option value="Hindu" @selected(old('religion') === 'Hindu')>Hindu</option>
                                <option value="Budha" @selected(old('religion') === 'Budha')>Budha</option>
                                <option value="Konghucu" @selected(old('religion') === 'Konghucu')>Konghucu</option>
                            </select>
                        </div>

                        <div class="premium-field">
                            <label for="teacher-instrument">Class / Instrumen yang diajar</label>
                            <input type="text" id="teacher-instrument" name="instrument" class="premium-input" value="{{ old('instrument') }}" placeholder="Contoh: Drum, Piano, Vocal, dll" required>
                            <small style="color: #64748b; margin-top: 0.25rem;">Ketik manual instrumen atau kelas yang akan diajar.</small>
                        </div>

                        <div class="premium-field full-width">
                            <label for="teacher-address">Alamat Domisili</label>
                            <textarea id="teacher-address" name="address" class="premium-textarea" placeholder="Tuliskan alamat lengkap..." required>{{ old('address') }}</textarea>
                        </div>

                        <div class="premium-field full-width">
                            <label>KTP Guru (Opsional)</label>
                            <input type="file" name="ktp" class="premium-input" accept="image/*" style="padding: 0.5rem;">
                        </div>

                        <div class="premium-field">
                            <label for="teacher-password">Password Akses</label>
                            <input type="text" id="teacher-password" name="password" class="premium-input" value="12345678" readonly required>
                            <small style="color: #64748b; margin-top: 0.25rem;">Default: <b>12345678</b> (Otomatis)</small>
                        </div>

                        <div class="premium-field">
                            <label for="teacher-password-confirm">Konfirmasi Password</label>
                            <input type="text" id="teacher-password-confirm" name="password_confirmation" class="premium-input" value="12345678" readonly required>
                        </div>
                    </div>
                </div>

                <footer class="premium-modal-footer">
                    <button type="button" class="btn-premium-secondary" onclick="closeTeacherModal()">Batal</button>
                    <button type="submit" class="btn-premium-primary" id="btn-submit-teacher">
                        <i data-lucide="check-circle"></i>
                        Simpan Teacher
                    </button>
                </footer>
            </form>
        </div>
    </div>

    <section class="card" data-searchable>
        <div class="card-header-flex">
            <h3>Daftar Guru</h3>
            <button type="button" class="btn-add-student" onclick="openTeacherModal()">
                <i data-lucide="user-plus"></i>
                Tambah Teacher Baru
            </button>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Nomor HP</th>
                        <th class="col-address">Alamat</th>
                        <th>Jenis Kelamin</th>
                        <th>Agama</th>
                        <th>Class</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($teachersForManagement as $teacher)
                        <tr>
                            <td data-label="Nama">{{ $teacher->name }}</td>
                            <td data-label="Email">{{ $teacher->user?->email ?? '-' }}</td>
                            <td data-label="Nomor HP">{{ $teacher->phone ?? '-' }}</td>
                            <td data-label="Alamat" class="col-address">{{ $teacher->address ?? '-' }}</td>
                            <td data-label="Jenis Kelamin">{{ $teacher->gender ?? '-' }}</td>
                            <td data-label="Agama">{{ $teacher->religion ?? '-' }}</td>
                            <td data-label="Class">{{ $teacher->classes->pluck('name')->implode(', ') ?: '-' }}</td>
                            <td data-label="Aksi">
                                <div class="action-icons">
                                    {{-- Detail Button --}}
                                    <details class="action-popover registration-style-popover">
                                        <summary class="btn-icon" title="Detail" aria-label="Detail"><i data-lucide="eye"></i></summary>
                                        <div class="action-popover-form registration-edit-form">
                                            <header class="registration-modal-header">
                                                <div class="registration-modal-header-left">
                                                    <span class="registration-modal-icon">
                                                        <i data-lucide="clipboard-list"></i>
                                                    </span>
                                                    <div>
                                                        <h3>Detail Guru</h3>
                                                        <p>Informasi lengkap profil pengajar</p>
                                                    </div>
                                                </div>
                                                <button type="button" class="registration-modal-close-btn" aria-label="Tutup" onclick="this.closest('details').removeAttribute('open');"><i data-lucide="x"></i></button>
                                            </header>
                                            <div class="registration-modal-body">
                                                <div class="registration-modal-summary">
                                                    <div class="registration-modal-summary-left">
                                                        @if($teacher->photo_path)
                                                            <img src="{{ asset('storage/' . $teacher->photo_path) }}" class="registration-modal-avatar" style="object-fit: cover;" onclick="showLightbox(this.src)">
                                                        @else
                                                            <div class="registration-modal-avatar">
                                                                {{ strtoupper(substr($teacher->name, 0, 1)) }}
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <p>Nama Guru</p>
                                                            <p class="registration-modal-summary-name">{{ $teacher->name }}</p>
                                                        </div>
                                                    </div>
                                                    <x-ui.badge :type="$teacher->is_active ? 'success' : 'warning'">
                                                        {{ $teacher->is_active ? 'ACTIVE' : 'INACTIVE' }}
                                                    </x-ui.badge>
                                                </div>
                                                <section class="registration-modal-grid">
                                                    <article class="registration-modal-item-full">
                                                        <p>Email Address</p>
                                                        <p>{{ $teacher->user?->email ?? '-' }}</p>
                                                    </article>
                                                    <article>
                                                        <p>Nomor HP</p>
                                                        <p>{{ $teacher->phone ?? '-' }}</p>
                                                    </article>
                                                    <article>
                                                        <p>Jenis Kelamin</p>
                                                        <p>{{ ucfirst($teacher->gender ?? '-') }}</p>
                                                    </article>
                                                    <article>
                                                        <p>Agama</p>
                                                        <p>{{ $teacher->religion ?? '-' }}</p>
                                                    </article>
                                                    <article>
                                                        <p>Bidang / Instrumen</p>
                                                        <p>{{ $teacher->instrument ?? '-' }}</p>
                                                    </article>
                                                    <article class="registration-modal-item-full">
                                                        <p>Alamat</p>
                                                        <p>{{ $teacher->address ?? '-' }}</p>
                                                    </article>
                                                    <article class="registration-modal-item-full">
                                                        <p>Kelas Diampu</p>
                                                        <p>{{ $teacher->classes->pluck('name')->join(', ') ?: '-' }}</p>
                                                    </article>
                                                    <article class="registration-modal-item-full">
                                                         <p>KTP Guru</p>
                                                         @if($teacher->ktp_path)
                                                            <div style="margin-top: 0.5rem;">
                                                                <img src="{{ asset('storage/' . $teacher->ktp_path) }}" style="width: 100%; max-height: 200px; object-fit: contain; border-radius: 0.5rem; border: 1px solid #e2e8f0; cursor: zoom-in;" onclick="showLightbox(this.src)">
                                                            </div>
                                                         @else
                                                            <div style="margin-top: 0.5rem; padding: 1rem; background: #fff1f2; border: 1px dashed #fecdd3; border-radius: 0.5rem; color: #be123c; font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem;">
                                                                <i data-lucide="alert-circle" style="width: 1rem; height: 1rem;"></i>
                                                                KTP belum diupload. Silakan edit data untuk melengkapi.
                                                            </div>
                                                         @endif
                                                     </article>
                                                </section>
                                            </div>
                                            <footer class="registration-modal-footer">
                                                <button type="button" class="registration-modal-btn registration-modal-btn-secondary action-popover-close"><i data-lucide="x" class="w-4 h-4"></i> Tutup</button>
                                                @if($teacher->user)
                                                    <a href="{{ route('super-admin.users.impersonate', $teacher->user->id) }}" class="registration-modal-btn" style="background: #0f172a !important; color: #fff !important; border: none !important;">
                                                        <i data-lucide="user-plus" class="w-4 h-4"></i> Login As
                                                    </a>
                                                @endif
                                                <button type="button" class="registration-modal-btn registration-modal-btn-primary" onclick="this.closest('.action-icons').querySelector('details:nth-child(2)').setAttribute('open', 'true'); this.closest('details').removeAttribute('open');"><i data-lucide="pencil" class="w-4 h-4"></i> Edit Data</button>
                                                <button type="button" class="registration-modal-btn registration-modal-btn-danger" onclick="if(confirm('Hapus teacher ini?')) this.closest('.action-icons').querySelector('form.delete-form').submit();"><i data-lucide="trash-2" class="w-4 h-4"></i> Hapus</button>
                                            </footer>
                                        </div>
                                    </details>
                                    <details class="action-popover" id="teacher-edit-{{ $teacher->id }}">
                                        <summary class="btn-icon" title="Edit" aria-label="Edit"><i data-lucide="pencil-line"></i></summary>
                                        <form class="action-popover-form teacher-edit-modal" method="POST" enctype="multipart/form-data" action="{{ route('super-admin.teachers.update', $teacher) }}" id="teacher-edit-form-{{ $teacher->id }}" novalidate>
                                            @csrf
                                            @method('PUT')
                                            {{-- ─── HEADER ─── --}}
                                            <header class="registration-modal-header">
                                                <div class="registration-modal-header-left">
                                                    <span class="registration-modal-icon">
                                                        <i data-lucide="user-pen"></i>
                                                    </span>
                                                    <div>
                                                        <h3>Edit Teacher</h3>
                                                        <p>Perbarui informasi data pengajar</p>
                                                    </div>
                                                </div>
                                                <button type="button" class="registration-modal-close-btn action-popover-close" aria-label="Tutup">
                                                    <i data-lucide="x"></i>
                                                </button>
                                            </header>

                                            {{-- ─── BODY ─── --}}
                                            <div class="registration-modal-body">
                                                <div class="module-form-grid">
                                                    <label>Nama
                                                        <input type="text" name="name" value="{{ $teacher->name }}" placeholder="Masukkan nama lengkap" required>
                                                    </label>
                                                    <label>Jenis Kelamin
                                                        <select name="gender" required>
                                                            <option value="" disabled>Pilih jenis kelamin</option>
                                                            <option value="laki-laki" @selected($teacher->gender === 'laki-laki')>Laki-laki</option>
                                                            <option value="perempuan" @selected($teacher->gender === 'perempuan')>Perempuan</option>
                                                        </select>
                                                    </label>
                                                    <label>Email
                                                        <input type="email" name="email" value="{{ $teacher->user?->email }}" placeholder="contoh@email.com" required>
                                                    </label>
                                                    <label>Agama
                                                        <input type="text" name="religion" value="{{ $teacher->religion }}" placeholder="Masukkan agama" required>
                                                    </label>
                                                    <label>Nomor HP
                                                        <input type="text" name="phone" value="{{ $teacher->phone }}" placeholder="08xxxxxxxxxx" required>
                                                    </label>
                                                    <label>Bidang / Instrumen
                                                        <input type="text" name="instrument" value="{{ $teacher->instrument }}" placeholder="Drum, Piano, Vocal, dll">
                                                    </label>
                                                    <label>Assign Class
                                                        <select name="class_id">
                                                            <option value="">Pilih class (opsional)</option>
                                                            @foreach ($classesForManagement as $classOption)
                                                                <option value="{{ $classOption->id }}" @selected($teacher->classes->contains($classOption->id))>{{ $classOption->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </label>
                                                    <label>Password Baru (opsional)
                                                        <input type="password" name="password" placeholder="Kosongkan jika tidak diganti">
                                                    </label>
                                                    <label>Upload Foto Profile
                                                        <input type="file" name="photo" accept="image/*">
                                                    </label>
                                                    <label style="grid-column: span 2;">Upload KTP Guru
                                                        <input type="file" name="ktp" accept="image/*">
                                                        @if($teacher->ktp_path)
                                                            <small style="color: #059669;">KTP sudah ada.</small>
                                                        @endif
                                                    </label>
                                                    <label style="grid-column: span 2;">Alamat
                                                        <textarea name="address" rows="3" placeholder="Masukkan alamat lengkap" required>{{ $teacher->address }}</textarea>
                                                    </label>
                                                </div>
                                            </div>

                                            {{-- ─── FOOTER ─── --}}
                                            <footer class="registration-modal-footer">
                                                <button type="button" class="registration-modal-btn registration-modal-btn-secondary action-popover-close"><i data-lucide="x"></i> Batal</button>
                                                <button type="submit" class="registration-modal-btn registration-modal-btn-primary">
                                                    <i data-lucide="save"></i> Simpan Perubahan
                                                </button>
                                            </footer>
                                        </form>
                                    </details>
                                    <form class="delete-form" method="POST" action="{{ route('super-admin.teachers.destroy', $teacher) }}" onsubmit="return confirm('Hapus teacher ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon btn-icon-danger" title="Hapus" aria-label="Hapus"><i data-lucide="trash-2"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">No teacher profiles yet. Add a teacher to start assigning classes.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endif

@if ($moduleKey === 'classes')

    <section class="card" id="form-create-class" style="display: @if($errors->any()) block @else none @endif;" data-searchable>
        <h3>Tambah Class Baru</h3>
        <form class="module-form module-form-grid teacher-create-form" method="POST" action="{{ route('super-admin.classes.store') }}">
                @csrf
                <label>Nama Kelas
                    <input type="text" name="name" value="{{ old('name') }}" required>
                </label>
                <label>Deskripsi
                    <textarea name="description" rows="3">{{ old('description') }}</textarea>
                </label>
                <label>Harga
                    <input type="number" name="price" min="0" step="1000" value="{{ old('price', 0) }}">
                </label>
                <label>Guru (Pilih satu atau lebih)
                    <select name="teacher_ids[]" multiple style="height: 120px;">
                        @foreach ($teachersForClassOptions as $teacherOption)
                            <option value="{{ $teacherOption->id }}" @selected(is_array(old('teacher_ids')) && in_array((string)$teacherOption->id, old('teacher_ids')))>{{ $teacherOption->name }}</option>
                        @endforeach
                    </select>
                    <small style="color: #64748b; font-size: 0.75rem;">Tahan Ctrl/Cmd untuk memilih lebih dari satu.</small>
                </label>
                <label>Status
                    <select name="status" required>
                        <option value="active" @selected(old('status', 'active') === 'active')>Active</option>
                        <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                    </select>
                </label>
                <div class="form-actions">
                    <button type="submit">Simpan Class</button>
                    <button type="reset" class="btn-secondary">Cancel</button>
                </div>
            </form>

    </section>

    <section class="card" data-searchable>
        <div class="card-header-flex">
            <h3>Daftar Class</h3>
            <button type="button" class="btn-add-student" onclick="const form = document.getElementById('form-create-class'); if(form) form.style.display = form.style.display === 'none' ? 'block' : 'none';">
                <i data-lucide="plus-circle"></i>
                Tambah Class Baru
            </button>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Guru</th>
                        <th>Harga</th>
                        <th>Jadwal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classesForManagement as $classItem)
                        <tr>
                            <td>{{ $classItem->name }}</td>
                            <td>{{ $classItem->teacher?->name ?? '-' }}</td>
                            <td>Rp{{ number_format((int) ($classItem->price ?? 0), 0, ',', '.') }}</td>
                            <td class="class-schedule-cell">
                                @php
                                    $bookedSchedules = $classItem->schedules->where('status', 'booked');
                                    $bookedDays = $bookedSchedules->pluck('day')->unique()->values();
                                    
                                    $dayOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                                    $sortedDays = $bookedDays->sortBy(function($day) use ($dayOrder) {
                                        return array_search($day, $dayOrder);
                                    });
                                @endphp

                                @if($sortedDays->isNotEmpty())
                                    <div class="schedule-tooltip-wrap">
                                        <span>{{ $sortedDays->implode(', ') }}</span>
                                        <div class="schedule-tooltip">
                                            <div class="tooltip-header"><i data-lucide="clock" style="width: 10px; height: 10px;"></i> Waktu Belajar Siswa</div>
                                            @foreach($sortedDays as $day)
                                                <div class="tooltip-row">
                                                    <span class="tooltip-day">{{ $day }}</span>
                                                    <span class="tooltip-time">
                                                        {{ $bookedSchedules->where('day', $day)->pluck('time')->map(fn($t) => substr((string)$t, 0, 5))->implode(', ') }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <span style="color: #cbd5e1;">-</span>
                                @endif
                            </td>
                            <td>
                                <x-ui.badge :type="$classItem->status === 'active' ? 'success' : 'warning'">
                                    {{ strtoupper($classItem->status) }}
                                </x-ui.badge>
                            </td>
                            <td>
                                <div class="action-icons class-action-icons">
                                    {{-- Detail Button --}}
                                    <details class="action-popover registration-style-popover">
                                        <summary class="btn-icon" title="Detail" aria-label="Detail"><i data-lucide="eye"></i></summary>
                                        <div class="action-popover-form registration-edit-form">
                                            <header class="registration-modal-header">
                                                <div class="registration-modal-header-left">
                                                    <span class="registration-modal-icon">
                                                        <i data-lucide="clipboard-list"></i>
                                                    </span>
                                                    <div>
                                                        <h3>Detail Kelas</h3>
                                                        <p>Informasi lengkap program kelas musik</p>
                                                    </div>
                                                </div>
                                                <button type="button" class="registration-modal-close-btn" aria-label="Tutup" onclick="this.closest('details').removeAttribute('open');"><i data-lucide="x"></i></button>
                                            </header>
                                            <div class="registration-modal-body">
                                                <div class="registration-modal-summary">
                                                    <div class="registration-modal-summary-left">
                                                        <div class="registration-modal-avatar">
                                                            {{ strtoupper(substr($classItem->name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <p>Nama Kelas</p>
                                                            <p class="registration-modal-summary-name">{{ $classItem->name }}</p>
                                                        </div>
                                                    </div>
                                                    <x-ui.badge :type="$classItem->status === 'active' ? 'success' : 'warning'">
                                                        {{ strtoupper($classItem->status) }}
                                                    </x-ui.badge>
                                                </div>
                                                <section class="registration-modal-grid">
                                                    <article>
                                                        <p>Harga Per Bulan</p>
                                                        <p>Rp{{ number_format((int) ($classItem->price ?? 0), 0, ',', '.') }}</p>
                                                    </article>
                                                    <article>
                                                        <p>Jadwal Standar</p>
                                                        <p>{{ $classItem->schedule ?? '-' }}</p>
                                                    </article>
                                                    <article class="registration-modal-item-full">
                                                        <p>Guru Pengampu</p>
                                                        <p>{{ $classItem->teachers->pluck('name')->join(', ') ?: ($classItem->teacher?->name ?? 'Belum ditentukan') }}</p>
                                                    </article>
                                                    <article class="registration-modal-item-full">
                                                        <p>Deskripsi Kelas</p>
                                                        <p>{{ $classItem->description ?: 'Tidak ada deskripsi' }}</p>
                                                    </article>
                                                </section>
                                            </div>
                                            <footer class="registration-modal-footer">
                                                <button type="button" class="registration-modal-btn registration-modal-btn-secondary action-popover-close">Tutup</button>
                                                <button type="button" class="registration-modal-btn registration-modal-btn-primary" onclick="this.closest('.action-icons').querySelector('details:nth-child(2)').setAttribute('open', 'true'); this.closest('details').removeAttribute('open');">Edit Kelas</button>
                                                <button type="button" class="registration-modal-btn registration-modal-btn-danger" onclick="if(confirm('Hapus class ini?')) this.closest('.action-icons').querySelector('form.delete-form').submit();">Hapus</button>
                                            </footer>
                                        </div>
                                    </details>

                                    <details class="action-popover registration-style-popover">
                                        <summary class="btn-icon" title="Edit" aria-label="Edit"><i data-lucide="pencil-line"></i></summary>
                                        <form class="action-popover-form registration-edit-form" method="POST" action="{{ route('super-admin.classes.update', $classItem) }}">
                                        @csrf
                                        @method('PUT')
                                        <header class="registration-modal-header">
                                            <div class="registration-modal-header-left">
                                                <span class="registration-modal-icon">
                                                    <i data-lucide="pencil-line"></i>
                                                </span>
                                                <div>
                                                    <h3>Edit Class</h3>
                                                    <p>Perbarui informasi kelas musik</p>
                                                </div>
                                            </div>
                                            <button type="button" class="registration-modal-close-btn action-popover-close" aria-label="Tutup"><i data-lucide="x"></i></button>
                                        </header>
                                        <div class="registration-modal-body">
                                            <div class="module-form-grid">
                                                <label style="grid-column: span 2;">Nama Kelas
                                                    <input type="text" name="name" value="{{ $classItem->name }}" required>
                                                </label>
                                                <label style="grid-column: span 2;">Deskripsi
                                                    <textarea name="description" rows="3">{{ $classItem->description }}</textarea>
                                                </label>
                                                <label>Harga
                                                    <input type="number" name="price" min="0" step="1000" value="{{ $classItem->price ?? 0 }}">
                                                </label>
                                                <label>Guru (Pilih satu atau lebih)
                                                    <select name="teacher_ids[]" multiple style="height: 120px;">
                                                        @foreach ($teachersForClassOptions as $teacherOption)
                                                            <option value="{{ $teacherOption->id }}" @selected($classItem->teachers->contains($teacherOption->id) || (string)$classItem->teacher_id === (string)$teacherOption->id)>{{ $teacherOption->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <small style="color: #64748b; font-size: 0.75rem;">Tahan Ctrl/Cmd untuk memilih lebih dari satu.</small>
                                                </label>
                                                <label style="grid-column: span 2;">Status
                                                    <select name="status" required>
                                                        <option value="active" @selected($classItem->status === 'active')>Active</option>
                                                        <option value="inactive" @selected($classItem->status === 'inactive')>Inactive</option>
                                                    </select>
                                                </label>
                                            </div>
                                        </div>
                                        <footer class="registration-modal-footer">
                                            <button type="button" class="registration-modal-btn registration-modal-btn-secondary action-popover-close">Batal</button>
                                            <button type="submit" class="registration-modal-btn registration-modal-btn-primary">Simpan Perubahan</button>
                                        </footer>
                                        </form>
                                    </details>
                                    <form class="delete-form" method="POST" action="{{ route('super-admin.classes.destroy', $classItem) }}" onsubmit="return confirm('Hapus class ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon btn-icon-danger" title="Hapus" aria-label="Hapus"><i data-lucide="trash-2"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No classes yet. Create a class and assign a teacher to begin operations.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endif

@if ($moduleKey === 'students')
    <section class="card" data-searchable>
        <div class="card-header-flex">
            <h3>Daftar Seluruh Siswa</h3>
            <button type="button" class="btn-add-student" onclick="const modal = document.getElementById('modal-create-student'); if(modal) modal.style.display = 'flex';">
                <i data-lucide="user-plus"></i>
                Tambah Siswa Baru
            </button>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Kelas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($studentsForManagement as $student)
                        <tr>
                            <td data-label="Nama">{{ $student->name }}</td>
                            <td data-label="Email">{{ $student->email ?: '-' }}</td>
                            <td data-label="Telepon">{{ $student->phone ?: '-' }}</td>
                            <td data-label="Kelas">
                                @if($student->class)
                                    {{ $student->class->name }}
                                @elseif($student->classes->isNotEmpty())
                                    {{ $student->classes->pluck('name')->join(', ') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td data-label="Status">
                                <x-ui.badge :type="$student->is_active ? 'success' : 'warning'">
                                    {{ $student->is_active ? 'ACTIVE' : 'INACTIVE' }}
                                </x-ui.badge>
                            </td>
                            <td data-label="Aksi">
                                <div class="action-icons">
                                    {{-- Detail Button --}}
                                    <details class="action-popover registration-style-popover">
                                        <summary class="btn-icon" title="Detail" aria-label="Detail"><i data-lucide="eye"></i></summary>
                                        <div class="action-popover-form registration-edit-form">
                                            <header class="registration-modal-header">
                                                <div class="registration-modal-header-left">
                                                    <span class="registration-modal-icon">
                                                        <i data-lucide="clipboard-list"></i>
                                                    </span>
                                                    <div>
                                                        <h3>Detail Siswa</h3>
                                                        <p>Informasi lengkap data siswa</p>
                                                    </div>
                                                </div>
                                                <button type="button" class="registration-modal-close-btn" aria-label="Tutup" onclick="this.closest('details').removeAttribute('open');"><i data-lucide="x"></i></button>
                                            </header>
                                            <div class="registration-modal-body">
                                                <div class="registration-modal-summary">
                                                    <div class="registration-modal-summary-left">
                                                        <div class="registration-modal-avatar">
                                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <p>Nama Siswa</p>
                                                            <p class="registration-modal-summary-name">{{ $student->name }}</p>
                                                        </div>
                                                    </div>
                                                    <span class="registration-status-badge {{ $student->is_active ? 'is-success' : 'is-warning' }}">
                                                        {{ $student->is_active ? 'ACTIVE' : 'INACTIVE' }}
                                                    </span>
                                                </div>
                                                <section class="registration-modal-grid">
                                                    <article><p>Nama Panggilan</p><p>{{ $student->nama_panggilan ?: '-' }}</p></article>
                                                    <article><p>Jenis Kelamin</p><p>{{ $student->jenis_kelamin ?: '-' }}</p></article>
                                                    <article><p>Tempat Lahir</p><p>{{ $student->tempat_lahir ?: '-' }}</p></article>
                                                    <article><p>Tanggal Lahir</p><p>{{ $student->tanggal_lahir ? \Carbon\Carbon::parse($student->tanggal_lahir)->format('d M Y') : '-' }}</p></article>
                                                    <article><p>Kewarganegaraan</p><p>{{ $student->kewarganegaraan ?: '-' }}</p></article>
                                                    <article><p>Umur</p><p>{{ $student->age ? $student->age . ' Tahun' : '-' }}</p></article>
                                                    <article><p>Email Siswa</p><p>{{ $student->email ?: '-' }}</p></article>
                                                    <article><p>No. HP Siswa</p><p>{{ $student->phone ?: '-' }}</p></article>
                                                    <article><p>Instagram Siswa</p><p>{{ $student->ig_siswa ?: '-' }}</p></article>
                                                    <article class="registration-modal-item-full"><p>Alamat Domisili</p><p class="text-wrap-normal">{{ $student->address ?: '-' }}</p></article>
                                                    
                                                    <article><p>Nama Orang Tua</p><p>{{ $student->nama_ortu ?: '-' }}</p></article>
                                                    <article><p>Pekerjaan Ortu</p><p>{{ $student->pekerjaan_ortu ?: '-' }}</p></article>
                                                    <article><p>No. HP Orang Tua</p><p>{{ $student->no_hp_ortu ?: '-' }}</p></article>
                                                    <article><p>Email Orang Tua</p><p>{{ $student->email_ortu ?: '-' }}</p></article>
                                                    <article><p>Instagram Ortu</p><p>{{ $student->ig_ortu ?: '-' }}</p></article>

                                                    <article class="registration-modal-item-full">
                                                        <p>Kelas Terdaftar</p>
                                                        <p>
                                                            @if($student->class)
                                                                {{ $student->class->name }}
                                                            @elseif($student->classes->isNotEmpty())
                                                                {{ $student->classes->pluck('name')->join(', ') }}
                                                            @else
                                                                -
                                                            @endif
                                                        </p>
                                                    </article>
                                                    <article><p>Mulai Kursus</p><p>{{ $student->start_date ? \Carbon\Carbon::parse($student->start_date)->format('d M Y') : '-' }}</p></article>
                                                    <article><p>Berakhir Pada</p><p>{{ $student->end_date ? \Carbon\Carbon::parse($student->end_date)->format('d M Y') : '-' }}</p></article>
                                                    
                                                    <article class="registration-modal-item-full">
                                                        <p>Program Tambahan</p>
                                                        <p>{{ is_array($student->program_tambahan) ? implode(', ', $student->program_tambahan) : '-' }}</p>
                                                    </article>
                                                    <article><p>Lagu Favorite</p><p>{{ $student->favorite_song ?: '-' }}</p></article>
                                                    <article><p>Pengalaman Musik</p><p>{{ $student->pengalaman ? 'Sudah Pernah' : 'Belum Pernah' }}</p></article>
                                                    <article class="registration-modal-item-full"><p>Deskripsi Pengalaman</p><p>{{ $student->deskripsi_pengalaman ?: '-' }}</p></article>
                                                </section>
                                            </div>
                                            <footer class="registration-modal-footer">
                                                <button type="button" class="registration-modal-btn registration-modal-btn-secondary action-popover-close"><i data-lucide="x" class="w-4 h-4"></i> Tutup</button>
                                                @if($student->user_id)
                                                    <a href="{{ route('super-admin.users.impersonate', $student->user_id) }}" class="registration-modal-btn" style="background: #0f172a !important; color: #fff !important; border: none !important;">
                                                        <i data-lucide="user-plus" class="w-4 h-4"></i> Login As
                                                    </a>
                                                @endif
                                                <button type="button" class="registration-modal-btn registration-modal-btn-primary" onclick="this.closest('.action-icons').querySelector('details:nth-child(2)').setAttribute('open', 'true'); this.closest('details').removeAttribute('open');"><i data-lucide="pencil" class="w-4 h-4"></i> Edit Data</button>
                                                <button type="button" class="registration-modal-btn registration-modal-btn-danger" onclick="if(confirm('Hapus siswa ini?')) this.closest('.action-icons').querySelector('form.delete-form').submit();"><i data-lucide="trash-2" class="w-4 h-4"></i> Hapus</button>
                                            </footer>
                                        </div>
                                    </details>

                                    {{-- Edit Button --}}
                                    <details class="action-popover registration-style-popover">
                                        <summary class="btn-icon" title="Edit" aria-label="Edit"><i data-lucide="pencil-line"></i></summary>
                                        <form class="action-popover-form registration-edit-form" method="POST" action="{{ route('super-admin.students.update', $student) }}">
                                            @csrf
                                            @method('PUT')
                                            <header class="registration-modal-header">
                                                <div class="registration-modal-header-left">
                                                    <span class="registration-modal-icon"><i data-lucide="pencil-line"></i></span>
                                                    <div><h3>Edit Data Siswa</h3><p>Perbarui informasi lengkap profil siswa</p></div>
                                                </div>
                                                <button type="button" class="registration-modal-close-btn" aria-label="Tutup" onclick="this.closest('details').removeAttribute('open');"><i data-lucide="x"></i></button>
                                            </header>
                                            <div class="registration-modal-body">
                                                <div class="module-form-grid" style="grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                                                    <label style="grid-column: span 2;">Nama Lengkap <input type="text" name="name" value="{{ $student->name }}" required></label>
                                                    <label>Nama Panggilan <input type="text" name="nama_panggilan" value="{{ $student->nama_panggilan }}"></label>
                                                    <label>Jenis Kelamin
                                                        <select name="jenis_kelamin">
                                                            <option value="laki-laki" @selected($student->jenis_kelamin === 'laki-laki')>Laki-laki</option>
                                                            <option value="perempuan" @selected($student->jenis_kelamin === 'perempuan')>Perempuan</option>
                                                        </select>
                                                    </label>
                                                    <label>Tempat Lahir <input type="text" name="tempat_lahir" value="{{ $student->tempat_lahir }}"></label>
                                                    <label>Tanggal Lahir <input type="date" name="tanggal_lahir" value="{{ $student->tanggal_lahir }}"> </label>
                                                    <label>Kewarganegaraan <input type="text" name="kewarganegaraan" value="{{ $student->kewarganegaraan }}"></label>
                                                    <label>Umur <input type="number" name="age" value="{{ $student->age }}"></label>
                                                    <label>No. HP Siswa <input type="text" name="phone" value="{{ $student->phone }}"></label>
                                                    <label>Instagram Siswa <input type="text" name="ig_siswa" value="{{ $student->ig_siswa }}" placeholder="@username"></label>
                                                    <label style="grid-column: span 2;">Email Siswa <input type="email" name="email" value="{{ $student->email }}"></label>
                                                    <label style="grid-column: span 2;">Alamat <textarea name="address" rows="2">{{ $student->address }}</textarea></label>
                                                    
                                                    <div style="grid-column: span 2; margin-top: 0.5rem; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 0.5rem;"><h4 style="font-size: 0.85rem; color: #818cf8;">Data Orang Tua</h4></div>
                                                    <label>Nama Ortu <input type="text" name="nama_ortu" value="{{ $student->nama_ortu }}"></label>
                                                    <label>Pekerjaan Ortu <input type="text" name="pekerjaan_ortu" value="{{ $student->pekerjaan_ortu }}"></label>
                                                    <label>No. HP Ortu <input type="text" name="no_hp_ortu" value="{{ $student->no_hp_ortu }}"></label>
                                                    <label>Email Ortu <input type="email" name="email_ortu" value="{{ $student->email_ortu }}"></label>
                                                    <label>Instagram Ortu <input type="text" name="ig_ortu" value="{{ $student->ig_ortu }}" placeholder="@username"></label>
                                                    
                                                    <div style="grid-column: span 2; margin-top: 0.5rem; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 0.5rem;"><h4 style="font-size: 0.85rem; color: #818cf8;">Akademik & Status</h4></div>
                                                    <label style="grid-column: span 2;">Kelas Terdaftar
                                                        <select name="class_ids[]" multiple style="height: 100px;">
                                                            @foreach($classesForManagement as $classItem)
                                                                <option value="{{ $classItem->id }}" @selected($student->classes->contains($classItem->id))>{{ $classItem->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </label>
                                                    <label>Mulai Kursus <input type="date" name="start_date" value="{{ $student->start_date }}"></label>
                                                    <label>Durasi (Bulan) <input type="number" name="duration_months" value="{{ $student->duration_months }}"></label>
                                                    <label>Status
                                                        <select name="is_active" required>
                                                            <option value="1" @selected($student->is_active)>Aktif</option>
                                                            <option value="0" @selected(!$student->is_active)>Tidak Aktif</option>
                                                        </select>
                                                    </label>
                                                    <label>Lagu Favorite <input type="text" name="favorite_song" value="{{ $student->favorite_song }}"></label>
                                                    <label>Pengalaman Musik
                                                        <select name="pengalaman">
                                                            <option value="0" @selected(!$student->pengalaman)>Belum Ada</option>
                                                            <option value="1" @selected($student->pengalaman)>Ada</option>
                                                        </select>
                                                    </label>
                                                    <label style="grid-column: span 2;">Deskripsi Pengalaman <textarea name="deskripsi_pengalaman" rows="2">{{ $student->deskripsi_pengalaman }}</textarea></label>
                                                </div>
                                            </div>
                                            <footer class="registration-modal-footer">
                                                <button type="button" class="registration-modal-btn registration-modal-btn-secondary" onclick="this.closest('details').removeAttribute('open');">Batal</button>
                                                <button type="submit" class="registration-modal-btn registration-modal-btn-primary">Simpan Perubahan</button>
                                            </footer>
                                        </form>
                                    </details>

                                    {{-- Delete Button --}}
                                    <form class="delete-form" method="POST" action="{{ route('super-admin.students.destroy', $student) }}" onsubmit="return confirm('Hapus siswa ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon btn-icon-danger" title="Hapus" aria-label="Hapus"><i data-lucide="trash-2"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Belum ada data siswa. Tambahkan siswa baru untuk menampilkannya di sini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endif

@if ($moduleKey === 'registrations')

    <style>
        /* Modern Firm Modal for Details/Popovers */
        details.action-popover {
            position: static;
        }

        details.action-popover[open] .action-popover-form {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: min(900px, 95vw);
            max-height: 90vh;
            background: #ffffff;
            border-radius: 2rem;
            z-index: 10000;
            display: flex;
            flex-direction: column;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.4);
            animation: modalPop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        @keyframes modalPop {
            from { opacity: 0; transform: translate(-50%, -45%) scale(0.95); }
            to { opacity: 1; transform: translate(-50%, -50%) scale(1); }
        }

        /* Backdrop for Details Modal */
        details.action-popover[open]::before {
            content: '';
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            z-index: 9999;
            cursor: pointer;
        }

        .registration-modal-header {
            padding: 2rem 2.5rem;
            background: linear-gradient(to right, #f8fafc, #ffffff);
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .registration-modal-header-left {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .registration-modal-icon {
            width: 3.5rem;
            height: 3.5rem;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2);
        }

        .registration-modal-header h3 {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.02em;
        }

        .registration-modal-header p {
            margin: 0.2rem 0 0 0;
            font-size: 0.9rem;
            color: #64748b;
        }

        .registration-modal-body {
            padding: 2rem 2.5rem;
            overflow-y: auto;
            flex: 1;
            background: #ffffff;
        }

        .registration-modal-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }

        .registration-modal-grid article {
            background: #f8fafc;
            padding: 1.25rem;
            border-radius: 1.25rem;
            border: 1px solid #f1f5f9;
            transition: all 0.2s;
        }

        .registration-modal-grid article:hover {
            background: #ffffff;
            border-color: #e2e8f0;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.02);
            transform: translateY(-2px);
        }

        .registration-modal-grid article p:first-child {
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 0.5rem;
            letter-spacing: 0.05em;
        }

        .registration-modal-grid article p:last-child {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .registration-modal-item-full {
            grid-column: span 3;
        }

        .registration-modal-footer {
            padding: 1.5rem 2.5rem;
            background: #f8fafc;
            border-top: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 1rem;
        }

        .registration-modal-btn {
            padding: 0.8rem 2rem;
            border-radius: 1rem;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .registration-modal-btn-primary {
            background: #6366f1;
            color: #fff;
            box-shadow: 0 10px 15px rgba(99, 102, 241, 0.2);
        }

        .registration-modal-btn-secondary {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            color: #64748b;
        }

        /* Highlight search results */
        [data-searchable] mark {
            background: #fef08a;
            color: #1e293b;
            padding: 0 2px;
            border-radius: 2px;
        }
    </style>

    <section class="card" id="form-create-registration" style="display: @if($errors->any() || $openRegistrationCreate) block @else none @endif;" data-searchable>
        <h3>Tambah Registration Baru</h3>
            <form class="module-form module-form-grid teacher-create-form" method="POST" action="{{ route('super-admin.registrations.store') }}">
                @csrf
                <label>Nama Lengkap
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required>
                </label>
                <label>Nama Panggilan
                    <input type="text" name="nama_panggilan" value="{{ old('nama_panggilan') }}" required>
                </label>
                <label>Jenis Kelamin
                    <select name="jenis_kelamin" required>
                        <option value="">Pilih jenis kelamin</option>
                        <option value="laki-laki" @selected(old('jenis_kelamin') === 'laki-laki')>laki-laki</option>
                        <option value="perempuan" @selected(old('jenis_kelamin') === 'perempuan')>perempuan</option>
                    </select>
                </label>
                <label>Tempat Lahir
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required>
                </label>
                <label>Tanggal Lahir
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                </label>
                <label>Kewarganegaraan
                    <input type="text" name="kewarganegaraan" value="{{ old('kewarganegaraan', 'Indonesia') }}" required>
                </label>
                <label>Alamat
                    <textarea name="alamat" rows="2" required>{{ old('alamat') }}</textarea>
                </label>
                <label>No HP Siswa
                    <input type="text" name="no_hp_siswa" value="{{ old('no_hp_siswa') }}" required>
                </label>
                <label>Instagram Siswa
                    <input type="text" name="ig_siswa" value="{{ old('ig_siswa') }}" placeholder="@username">
                </label>
                <label>Email
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </label>
                <label>Nama Orang Tua
                    <input type="text" name="nama_ortu" value="{{ old('nama_ortu') }}" required>
                </label>
                <label>Pekerjaan Orang Tua
                    <input type="text" name="pekerjaan_ortu" value="{{ old('pekerjaan_ortu') }}">
                </label>
                <label>No HP Orang Tua
                    <input type="text" name="no_hp_ortu" value="{{ old('no_hp_ortu') }}" required>
                </label>
                <label>Email Orang Tua
                    <input type="email" name="email_ortu" value="{{ old('email_ortu') }}">
                </label>
                <label>Instagram Orang Tua
                    <input type="text" name="ig_ortu" value="{{ old('ig_ortu') }}" placeholder="@username_ortu">
                </label>
                <label>Instrumen
                    <select name="instrumen" id="reg-create-instrumen" required>
                        <option value="">Pilih instrumen</option>
                        @foreach($instrumenOptions as $instrumenItem)
                            <option value="{{ $instrumenItem }}" @selected(old('instrumen') === $instrumenItem)>{{ $instrumenItem }}</option>
                        @endforeach
                    </select>
                </label>
                <label id="reg-create-favorite-song-group">Lagu Favorite
                    <input type="text" name="favorite_song" value="{{ old('favorite_song') }}" placeholder="Contoh: Heal The World">
                </label>
                <label>Program Tambahan
                    <select name="program_tambahan[]" multiple>
                        @foreach($programTambahanOptions as $programItem)
                            <option value="{{ $programItem }}" @selected(in_array($programItem, old('program_tambahan', []), true))>{{ $programItem }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Hari Pilihan
                    <select name="hari_pilihan[]" multiple required>
                        @foreach($hariOptions as $hariItem)
                            <option value="{{ $hariItem }}" @selected(in_array($hariItem, old('hari_pilihan', []), true))>{{ $hariItem }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Pernah Belajar Musik
                    <select name="pengalaman" required>
                        <option value="1" @selected(old('pengalaman') === '1')>Ya</option>
                        <option value="0" @selected(old('pengalaman') === '0')>Tidak</option>
                    </select>
                </label>
                <label>Deskripsi Pengalaman
                    <textarea name="deskripsi_pengalaman" rows="2">{{ old('deskripsi_pengalaman') }}</textarea>
                </label>
                <label>Kelas
                    <select name="class_id">
                        <option value="">Pilih kelas</option>
                        @foreach($classesForManagement as $classItem)
                            <option value="{{ $classItem->id }}" @selected((string) old('class_id') === (string) $classItem->id)>{{ $classItem->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Status
                    <select name="status" required>
                        <option value="pending" @selected(old('status', 'pending') === 'pending')>pending</option>
                        <option value="accepted" @selected(old('status') === 'accepted')>accepted</option>
                        <option value="rejected" @selected(old('status') === 'rejected')>rejected</option>
                    </select>
                </label>
                <div class="form-actions">
                    <button type="submit">Simpan Registration</button>
                    <button type="reset" class="btn-secondary">Cancel</button>
                </div>
            </form>
    </section>

    <section class="card" data-searchable>
        <div class="card-header-flex">
            <h3>Daftar Registration</h3>
            <button type="button" class="btn-add-student" onclick="const form = document.getElementById('form-create-registration'); if(form) form.style.display = form.style.display === 'none' ? 'block' : 'none';">
                <i data-lucide="plus-circle"></i>
                Tambah Registration Baru
            </button>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Instrumen</th>
                        <th>Jadwal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrationsForManagement as $registrationItem)
                        @php
                            $registrationStatus = strtolower((string) $registrationItem->status);
                            $registrationBadge = $registrationStatus === 'accepted' ? 'success' : ($registrationStatus === 'rejected' ? 'danger' : 'warning');
                            $legacyNotesMap = [];
                            foreach (preg_split('/\r\n|\r|\n/', (string) ($registrationItem->notes ?? '')) as $line) {
                                $line = trim((string) $line);
                                if ($line === '' || ! str_contains($line, ':')) {
                                    continue;
                                }

                                [$key, $value] = array_pad(explode(':', $line, 2), 2, '');
                                $key = trim($key);
                                if ($key === '') {
                                    continue;
                                }

                                $legacyNotesMap[$key] = trim($value);
                            }

                            $namaLengkap = $registrationItem->nama_lengkap ?: $registrationItem->full_name;
                            $namaPanggilan = $registrationItem->nama_panggilan ?: ($legacyNotesMap['Nama Panggilan'] ?? '-');
                            $jenisKelamin = $registrationItem->jenis_kelamin ?: ($legacyNotesMap['Jenis Kelamin'] ?? '-');

                            $legacyTempatLahir = '-';
                            $legacyTanggalLahir = '-';
                            $legacyTempatTanggal = trim((string) ($legacyNotesMap['Tempat/Tanggal Lahir'] ?? ''));
                            if ($legacyTempatTanggal !== '') {
                                $legacyTempatTanggalParts = array_values(array_filter(array_map('trim', explode(',', $legacyTempatTanggal)), fn (string $item) => $item !== ''));
                                if (count($legacyTempatTanggalParts) >= 2) {
                                    $legacyTanggalLahir = array_pop($legacyTempatTanggalParts) ?: '-';
                                    $legacyTempatLahir = implode(', ', $legacyTempatTanggalParts) ?: '-';
                                } else {
                                    $legacyTempatLahir = $legacyTempatTanggal;
                                }
                            }

                            $tempatLahir = $registrationItem->tempat_lahir ?: $legacyTempatLahir;

                            $tanggalLahirText = $registrationItem->tanggal_lahir
                                ? optional($registrationItem->tanggal_lahir)->format('d M Y')
                                : $legacyTanggalLahir;

                            $tanggalLahirInput = $registrationItem->tanggal_lahir
                                ? optional($registrationItem->tanggal_lahir)->format('Y-m-d')
                                : null;
                            if (! $tanggalLahirInput && $legacyTanggalLahir !== '-') {
                                try {
                                    $tanggalLahirInput = \Carbon\Carbon::parse($legacyTanggalLahir)->format('Y-m-d');
                                } catch (\Throwable $e) {
                                    $tanggalLahirInput = '';
                                }
                            }

                            $kewarganegaraan = $registrationItem->kewarganegaraan ?: ($legacyNotesMap['Kewarganegaraan'] ?? '-');
                            $alamat = $registrationItem->alamat ?: ($legacyNotesMap['Alamat'] ?? '-');
                            $teleponSiswa = $registrationItem->no_hp_siswa ?: $registrationItem->phone;
                            $namaOrtu = $registrationItem->nama_ortu ?: ($legacyNotesMap['Nama Ortu'] ?? '-');
                            $pekerjaanOrtu = $registrationItem->pekerjaan_ortu ?: ($legacyNotesMap['Pekerjaan Ortu'] ?? '-');
                            $noHpOrtu = $registrationItem->no_hp_ortu ?: ($legacyNotesMap['No HP Ortu'] ?? '-');
                            $emailOrtu = $registrationItem->email_ortu ?: ($legacyNotesMap['Email Ortu'] ?? '-');

                            $instrumenValue = $registrationItem->instrumen ?: ($legacyNotesMap['Instrumen'] ?? '');
                            $instrumenText = $instrumenValue !== '' ? $instrumenValue : ($registrationItem->class?->name ?? '-');

                            $hariPilihanText = $registrationItem->preferred_schedule ?? '-';
                            $hariPilihanArray = is_array($registrationItem->hari_pilihan)
                                ? $registrationItem->hari_pilihan
                                : collect(explode(',', (string) ($registrationItem->preferred_schedule ?? '')))
                                    ->map(fn (string $item) => trim($item))
                                    ->filter()
                                    ->values()
                                    ->all();
                            if (empty($hariPilihanArray) && ! empty($legacyNotesMap['Hari Pilihan'])) {
                                $hariPilihanArray = collect(explode(',', (string) $legacyNotesMap['Hari Pilihan']))
                                    ->map(fn (string $item) => trim($item))
                                    ->filter()
                                    ->values()
                                    ->all();
                            }
                            if (! empty($hariPilihanArray)) {
                                $hariPilihanText = implode(', ', $hariPilihanArray);
                            }

                            $programTambahanArray = is_array($registrationItem->program_tambahan)
                                ? $registrationItem->program_tambahan
                                : [];
                            if (empty($programTambahanArray) && ! empty($legacyNotesMap['Program Tambahan']) && $legacyNotesMap['Program Tambahan'] !== '-') {
                                $programTambahanArray = collect(explode(',', (string) $legacyNotesMap['Program Tambahan']))
                                    ->map(fn (string $item) => trim($item))
                                    ->filter()
                                    ->values()
                                    ->all();
                            }
                            $programTambahanText = ! empty($programTambahanArray) ? implode(', ', $programTambahanArray) : '-';

                            $pengalamanValue = null;
                            if (! is_null($registrationItem->pengalaman)) {
                                $pengalamanValue = (bool) $registrationItem->pengalaman;
                            } elseif (! empty($legacyNotesMap['Pengalaman']) && $legacyNotesMap['Pengalaman'] !== '-') {
                                $pengalamanValue = in_array(strtolower((string) $legacyNotesMap['Pengalaman']), ['ya', 'yes', '1', 'true'], true);
                            }
                            $pengalamanText = is_null($pengalamanValue) ? '-' : ($pengalamanValue ? 'Ya' : 'Tidak');

                            $deskripsiPengalaman = $registrationItem->deskripsi_pengalaman ?: ($legacyNotesMap['Deskripsi Pengalaman'] ?? null);
                            if (blank($deskripsiPengalaman) && ! empty($registrationItem->notes) && empty($legacyNotesMap)) {
                                $deskripsiPengalaman = $registrationItem->notes;
                            }
                            $deskripsiPengalaman = $deskripsiPengalaman ?: '-';

                            $favoriteSong = $registrationItem->favorite_song ?: ($legacyNotesMap['Lagu Favorite'] ?? '-');

                            $editTriggerId = 'registration-edit-trigger-'.$registrationItem->id;
                            $detailPayload = [
                                'fullName' => $namaLengkap,
                                'nickName' => $namaPanggilan,
                                'gender' => $jenisKelamin,
                                'birthPlace' => $tempatLahir,
                                'birthDate' => $tanggalLahirText,
                                'birthDateInput' => $tanggalLahirInput,
                                'citizenship' => $kewarganegaraan,
                                'studentPhone' => $teleponSiswa,
                                'studentEmail' => $registrationItem->email,
                                'address' => $alamat,
                                'parentName' => $namaOrtu,
                                'parentJob' => $pekerjaanOrtu,
                                'parentPhone' => $noHpOrtu,
                                'parentEmail' => $emailOrtu,
                                'instrument' => $instrumenText,
                                'additionalProgram' => $programTambahanText,
                                'preferredDays' => $hariPilihanText,
                                'preferredDaysRaw' => $hariPilihanArray,
                                'experience' => $pengalamanText,
                                'experienceValue' => $pengalamanValue === true ? '1' : '0',
                                'experienceDescription' => $deskripsiPengalaman,
                                'status' => strtoupper($registrationStatus),
                                'statusValue' => $registrationStatus,
                                'selectedClass' => $registrationItem->class?->name ?? '-',
                                'classId' => (string) ($registrationItem->class_id ?? ''),
                                'instrumentValue' => $instrumenValue,
                                'additionalProgramRaw' => $programTambahanArray,
                                'updateAction' => route('super-admin.registrations.update', $registrationItem),
                                'editTriggerId' => $editTriggerId,
                                'deleteAction' => route('super-admin.registrations.destroy', $registrationItem),
                                'schedules' => $registrationItem->schedules->map(fn($s) => [
                                    'label' => $s->day . ' ' . substr((string)$s->time, 0, 5)
                                ])->all(),
                            ];
                        @endphp
                        <tr>
                            <td data-label="Nama">{{ $namaLengkap }}</td>
                            <td data-label="Email">{{ $registrationItem->email }}</td>
                            <td data-label="Telepon">{{ $teleponSiswa }}</td>
                            <td data-label="Instrumen">{{ $instrumenText }}</td>
                            <td data-label="Jadwal">
                                @if($registrationItem->schedules->isNotEmpty())
                                    <div style="display: flex; flex-direction: column; gap: 4px;">
                                        @foreach($registrationItem->schedules as $sch)
                                            <span class="registration-schedule-count" style="display: block; font-size: 10px; white-space: nowrap;">
                                                {{ $sch->day }} {{ substr((string)$sch->time, 0, 5) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @elseif($registrationItem->schedule_id)
                                    @php $sch = \App\Models\Schedule::find($registrationItem->schedule_id); @endphp
                                    <span class="registration-schedule-count">{{ $sch ? $sch->day . ' ' . substr((string)$sch->time, 0, 5) : '-' }}</span>
                                @else
                                    <span class="registration-schedule-count">-</span>
                                @endif
                            </td>
                            <td data-label="Status"><x-ui.badge :type="$registrationBadge">{{ strtoupper($registrationStatus) }}</x-ui.badge></td>
                            <td data-label="Aksi">
                                <div class="action-icons class-action-icons">
                                    @if ($registrationStatus !== 'accepted')
                                        <form method="POST" action="{{ route('super-admin.registrations.approve', $registrationItem->id) }}" onsubmit="return confirm('Approve registration ini dan buat akun siswa?');">
                                            @csrf
                                            <button type="submit" class="btn-icon" title="Approve" aria-label="Approve"><i data-lucide="badge-check"></i></button>
                                        </form>
                                    @endif

                                    {{-- Detail Popover --}}
                                    <details class="action-popover registration-style-popover">
                                        <summary class="btn-icon" title="Detail" aria-label="Detail"><i data-lucide="eye"></i></summary>
                                        <div class="action-popover-form registration-edit-form">
                                            <header class="registration-modal-header">
                                                <div class="registration-modal-header-left">
                                                    <span class="registration-modal-icon"><i data-lucide="clipboard-list"></i></span>
                                                    <div><h3>Detail Pendaftaran</h3><p>Informasi lengkap data siswa</p></div>
                                                </div>
                                                <button type="button" class="registration-modal-close-btn" aria-label="Tutup" onclick="this.closest('details').removeAttribute('open');"><i data-lucide="x"></i></button>
                                            </header>
                                            <div class="registration-modal-body">
                                                <div class="registration-modal-summary">
                                                    <div class="registration-modal-summary-left">
                                                        <div class="registration-modal-avatar">
                                                            {{ strtoupper(substr($namaLengkap, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <p>Nama Siswa</p>
                                                            <p class="registration-modal-summary-name">{{ $namaLengkap }}</p>
                                                        </div>
                                                    </div>
                                                    <x-ui.badge :type="$registrationBadge">{{ strtoupper($registrationStatus) }}</x-ui.badge>
                                                </div>
                                                <section class="registration-modal-grid">
                                                    <article><p>Nama Panggilan</p><p>{{ $namaPanggilan }}</p></article>
                                                    <article><p>Jenis Kelamin</p><p>{{ $jenisKelamin }}</p></article>
                                                    <article><p>Tempat Lahir</p><p>{{ $tempatLahir }}</p></article>
                                                    <article><p>Tanggal Lahir</p><p>{{ $tanggalLahirText }}</p></article>
                                                    <article><p>Kewarganegaraan</p><p>{{ $kewarganegaraan }}</p></article>
                                                    <article><p>No HP Siswa</p><p>{{ $teleponSiswa }}</p></article>
                                                    <article><p>Instagram Siswa</p><p>{{ $registrationItem->ig_siswa ?: '-' }}</p></article>
                                                    <article><p>Instagram Ortu</p><p>{{ $registrationItem->ig_ortu ?: '-' }}</p></article>
                                                    <article class="registration-modal-item-full"><p>Email Siswa</p><p>{{ $registrationItem->email }}</p></article>
                                                    <article class="registration-modal-item-full"><p>Alamat</p><p class="text-wrap-normal">{{ $alamat }}</p></article>
                                                    <article><p>Nama Orang Tua</p><p>{{ $namaOrtu }}</p></article>
                                                    <article><p>Pekerjaan Orang Tua</p><p>{{ $pekerjaanOrtu }}</p></article>
                                                    <article><p>No HP Orang Tua</p><p>{{ $noHpOrtu }}</p></article>
                                                    <article><p>Email Orang Tua</p><p>{{ $emailOrtu }}</p></article>
                                                    <article><p>Instrumen</p><p>{{ $instrumenText }}</p></article>
                                                    <article><p>Lagu Favorite</p><p>{{ $favoriteSong }}</p></article>
                                                    <article><p>Program Tambahan</p><p>{{ $programTambahanText }}</p></article>
                                                    <article><p>Mulai Belajar</p><p>{{ $registrationItem->start_date ? \Carbon\Carbon::parse($registrationItem->start_date)->format('d M Y') : '-' }}</p></article>
                                                    <article><p>Durasi Paket</p><p>{{ $registrationItem->duration_months ?? '-' }} Bulan</p></article>
                                                    <article><p>Jadwal Belajar</p>
                                                        <p>
                                                            @php
                                                                $regSchedules = $registrationItem->schedules;
                                                                $isDouble = $regSchedules->count() > 1;
                                                            @endphp
                                                            <span style="display:block; font-weight: bold; color: {{ $isDouble ? '#6366f1' : '#64748b' }}; margin-bottom: 2px;">
                                                                {{ $isDouble ? 'DOUBLE TIME' : 'SINGLE TIME' }}
                                                            </span>
                                                            @forelse($regSchedules as $sch)
                                                                {{ $sch->day }} {{ substr((string)$sch->time, 0, 5) }}{{ !$loop->last ? ',' : '' }}
                                                            @empty
                                                                {{ $registrationItem->preferred_schedule ?: '-' }}
                                                            @endforelse
                                                        </p>
                                                    </article>
                                                    <article><p>Pengalaman Belajar</p><p>{{ $pengalamanText }}</p></article>
                                                    <article class="registration-modal-item-full"><p>Deskripsi Pengalaman</p><p>{{ $deskripsiPengalaman }}</p></article>
                                                    <article><p>Status</p><p>{{ strtoupper($registrationStatus) }}</p></article>
                                                    <article><p>Kelas Terpilih</p><p>{{ $registrationItem->class?->name ?? '-' }}</p></article>
                                                </section>
                                            </div>
                                            <footer class="registration-modal-footer">
                                                <button type="button" class="registration-modal-btn registration-modal-btn-secondary" onclick="this.closest('details').removeAttribute('open');">Tutup</button>
                                                <button type="button" class="registration-modal-btn registration-modal-btn-primary" onclick="this.closest('.action-icons').querySelector('details:nth-child(3)').setAttribute('open', 'true'); this.closest('details').removeAttribute('open');">Edit Data</button>
                                            </footer>
                                        </div>
                                    </details>

                                    {{-- Edit Popover --}}
                                    <details class="action-popover registration-style-popover">
                                        <summary class="btn-icon" title="Edit" aria-label="Edit"><i data-lucide="pencil-line"></i></summary>
                                        <form class="action-popover-form registration-edit-form" method="POST" action="{{ route('super-admin.registrations.update', $registrationItem) }}">
                                            @csrf
                                            @method('PUT')
                                            <header class="registration-modal-header">
                                                <div class="registration-modal-header-left">
                                                    <span class="registration-modal-icon"><i data-lucide="pencil-line"></i></span>
                                                    <div><h3>Edit Pendaftaran</h3><p>Perbarui informasi pendaftaran siswa</p></div>
                                                </div>
                                                <button type="button" class="registration-modal-close-btn" aria-label="Tutup" onclick="this.closest('details').removeAttribute('open');"><i data-lucide="x"></i></button>
                                            </header>
                                            <div class="registration-modal-body">
                                                <div class="module-form-grid">
                                                    <label>Nama Lengkap <input type="text" name="nama_lengkap" value="{{ $namaLengkap }}" required></label>
                                                    <label>Nama Panggilan <input type="text" name="nama_panggilan" value="{{ $namaPanggilan }}" required></label>
                                                    <label>Jenis Kelamin
                                                        <select name="jenis_kelamin" required>
                                                            <option value="laki-laki" @selected($jenisKelamin === 'laki-laki')>Laki-laki</option>
                                                            <option value="perempuan" @selected($jenisKelamin === 'perempuan')>Perempuan</option>
                                                        </select>
                                                    </label>
                                                    <label>Tempat Lahir <input type="text" name="tempat_lahir" value="{{ $tempatLahir }}" required></label>
                                                    <label>Tanggal Lahir <input type="date" name="tanggal_lahir" value="{{ $tanggalLahirInput }}" required></label>
                                                    <label>Kewarganegaraan <input type="text" name="kewarganegaraan" value="{{ $kewarganegaraan }}" required></label>
                                                    <label style="grid-column: span 2;">Alamat <textarea name="alamat" rows="3" required>{{ $alamat }}</textarea></label>
                                                    <label>No HP Siswa <input type="tel" name="no_hp_siswa" value="{{ $teleponSiswa }}" required></label>
                                                    <label>Instagram Siswa <input type="text" name="ig_siswa" value="{{ $registrationItem->ig_siswa }}" placeholder="@username"></label>
                                                    <label>Email Siswa <input type="email" name="email" value="{{ $registrationItem->email }}" required></label>
                                                    <label>Nama Orang Tua <input type="text" name="nama_ortu" value="{{ $namaOrtu }}" required></label>
                                                    <label>Pekerjaan Orang Tua <input type="text" name="pekerjaan_ortu" value="{{ $pekerjaanOrtu }}"></label>
                                                    <label>No HP Orang Tua <input type="tel" name="no_hp_ortu" value="{{ $noHpOrtu }}" required></label>
                                                    <label>Email Orang Tua <input type="email" name="email_ortu" value="{{ $emailOrtu }}"></label>
                                                    <label>Instagram Ortu <input type="text" name="ig_ortu" value="{{ $registrationItem->ig_ortu }}" placeholder="@username"></label>
                                                    <label>Instrumen
                                                        <select name="instrumen" required>
                                                            @foreach($instrumenOptions as $instrumenItem)
                                                                <option value="{{ $instrumenItem }}" @selected($instrumenValue === $instrumenItem)>{{ $instrumenItem }}</option>
                                                            @endforeach
                                                        </select>
                                                    </label>
                                                    <label id="reg-edit-favorite-song-{{ $registrationItem->id }}">Lagu Favorite
                                                        <input type="text" name="favorite_song" value="{{ $registrationItem->favorite_song ?: ($legacyNotesMap['Lagu Favorite'] ?? '') }}" placeholder="Contoh: Heal The World">
                                                    </label>

                                                    <div style="grid-column: span 2; margin-top: 0.5rem;">
                                                        <label class="premium-label" style="font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 0.75rem; display: block;">Pilih Jadwal (Double Time: pilih > 1)</label>
                                                        
                                                        <div class="registration-schedule-container">
                                                            @php
                                                                $currentScheduleIds = $registrationItem->schedules->pluck('id')->toArray();
                                                                $relevantSchedules = $schedulesForManagement->where('class_id', $registrationItem->class_id);
                                                                $allSelectable = $relevantSchedules->merge($registrationItem->schedules)->unique('id')->sortBy(function($s) {
                                                                    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                                                                    return array_search($s->day, $days);
                                                                });
                                                                $groupedByDay = $allSelectable->groupBy('day');
                                                            @endphp

                                                            @forelse($groupedByDay as $day => $slots)
                                                                <div class="reg-day-group">
                                                                    <div class="reg-day-header">{{ $day }}</div>
                                                                    <div class="reg-day-slots">
                                                                        @foreach($slots as $sch)
                                                                            @php
                                                                                $isSelected = in_array($sch->id, $currentScheduleIds);
                                                                                $isFull = $sch->status === 'booked' && !$isSelected;
                                                                            @endphp
                                                                            <label class="reg-slot-card {{ $isSelected ? 'is-selected' : '' }} {{ $isFull ? 'is-disabled' : '' }}">
                                                                                <input type="checkbox" name="schedule_ids[]" value="{{ $sch->id }}" @checked($isSelected) @disabled($isFull) style="display: none;" onchange="this.parentElement.classList.toggle('is-selected', this.checked)">
                                                                                <span class="reg-slot-time">{{ substr((string)$sch->time, 0, 5) }}</span>
                                                                                @if($isFull)
                                                                                    <span class="reg-slot-badge">FULL</span>
                                                                                @endif
                                                                            </label>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @empty
                                                                <div class="reg-empty-state">
                                                                    <i data-lucide="calendar-x2"></i>
                                                                    <span>Tidak ada jadwal tersedia untuk instrumen ini.</span>
                                                                </div>
                                                            @endforelse
                                                        </div>
                                                    </div>

                                                    <style>
                                                        .registration-schedule-container {
                                                            display: flex;
                                                            flex-direction: column;
                                                            gap: 1.25rem;
                                                            padding: 1.25rem;
                                                            background: #f8fafc;
                                                            border: 1px solid #e2e8f0;
                                                            border-radius: 1.25rem;
                                                            max-height: 320px;
                                                            overflow-y: auto;
                                                        }
                                                        .reg-day-group {
                                                            display: flex;
                                                            flex-direction: column;
                                                            gap: 0.75rem;
                                                        }
                                                        .reg-day-header {
                                                            font-size: 10px;
                                                            font-weight: 800;
                                                            text-transform: uppercase;
                                                            color: #64748b;
                                                            letter-spacing: 0.05em;
                                                            display: flex;
                                                            align-items: center;
                                                            gap: 0.5rem;
                                                        }
                                                        .reg-day-header::after {
                                                            content: '';
                                                            flex: 1;
                                                            height: 1px;
                                                            background: #e2e8f0;
                                                        }
                                                        .reg-day-slots {
                                                            display: grid;
                                                            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
                                                            gap: 0.5rem;
                                                        }
                                                        .reg-slot-card {
                                                            display: flex;
                                                            flex-direction: column;
                                                            align-items: center;
                                                            justify-content: center;
                                                            padding: 0.6rem;
                                                            background: #ffffff;
                                                            border: 1.5px solid #edf2f7;
                                                            border-radius: 12px;
                                                            cursor: pointer;
                                                            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
                                                        }
                                                        .reg-slot-card:hover:not(.is-disabled) {
                                                            border-color: #6366f1;
                                                            transform: translateY(-2px);
                                                            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.08);
                                                        }
                                                        .reg-slot-card.is-selected {
                                                            background: #6366f1;
                                                            border-color: #6366f1;
                                                            color: #ffffff;
                                                            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
                                                        }
                                                        .reg-slot-card.is-disabled {
                                                            opacity: 0.4;
                                                            cursor: not-allowed;
                                                            background: #f1f5f9;
                                                            border-style: dashed;
                                                        }
                                                        .reg-slot-time {
                                                            font-size: 12px;
                                                            font-weight: 800;
                                                        }
                                                        .reg-slot-badge {
                                                            font-size: 7px;
                                                            font-weight: 900;
                                                            margin-top: 2px;
                                                            background: rgba(0,0,0,0.1);
                                                            padding: 1px 4px;
                                                            border-radius: 4px;
                                                        }
                                                        .reg-empty-state {
                                                            text-align: center;
                                                            padding: 2rem;
                                                            color: #94a3b8;
                                                            font-size: 11px;
                                                        }
                                                    </style>
                                                    <label>Status
                                                        <select name="status" required>
                                                            <option value="pending" @selected($registrationStatus === 'pending')>pending</option>
                                                            <option value="accepted" @selected($registrationStatus === 'accepted')>accepted</option>
                                                            <option value="rejected" @selected($registrationStatus === 'rejected')>rejected</option>
                                                        </select>
                                                    </label>
                                                </div>
                                            </div>
                                            <footer class="registration-modal-footer">
                                                <button type="button" class="registration-modal-btn registration-modal-btn-secondary" onclick="this.closest('details').removeAttribute('open');">Batal</button>
                                                <button type="submit" class="registration-modal-btn registration-modal-btn-primary">Simpan Perubahan</button>
                                            </footer>
                                        </form>
                                    </details>

                                    <form method="POST" action="{{ route('super-admin.registrations.destroy', $registrationItem) }}" onsubmit="return confirm('Hapus registration ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon btn-icon-danger" title="Hapus" aria-label="Hapus"><i data-lucide="trash-2"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No registrations yet. Website leads will appear here automatically.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>





    <form method="POST" data-registration-modal-delete-form style="display:none;">
        @csrf
        @method('DELETE')
    </form>

@endif

@if ($moduleKey === 'blog')
    <section class="card" id="form-create-blog" style="display: @if($errors->any()) block @else none @endif;" data-searchable>
        <h3>Tambah Post Baru</h3>
        <form class="module-form module-form-grid" method="POST" action="{{ route('super-admin.content.store', 'blog') }}">
            @csrf
            <label>Title<input type="text" name="title" required></label>
            <label>Slug<input type="text" name="slug" required></label>
            <label>Excerpt<textarea name="excerpt" rows="2"></textarea></label>
            <label>Content<textarea name="content" rows="4"></textarea></label>
            <label>Cover Image URL<input type="text" name="cover_image"></label>
            <label>Status
                <select name="status"><option value="draft">draft</option><option value="published">published</option></select>
            </label>
            <label>Published At<input type="datetime-local" name="published_at"></label>
            <div class="form-actions">
                <button type="submit">Simpan Post</button>
                <button type="reset" class="btn-secondary">Cancel</button>
            </div>
        </form>
    </section>
    <section class="card" data-searchable>
        <div class="card-header-flex">
            <h3>Daftar Post</h3>
            <button type="button" class="btn-add-student" onclick="const form = document.getElementById('form-create-blog'); if(form) form.style.display = form.style.display === 'none' ? 'block' : 'none';">
                <i data-lucide="plus-circle"></i>
                Tambah Post Baru
            </button>
        </div>
        @foreach($postsForManagement as $post)
            <form class="module-form" method="POST" action="{{ route('super-admin.content.update', ['module' => 'blog', 'id' => $post->id]) }}">
                @csrf
                @method('PUT')
                <label>Title<input type="text" name="title" value="{{ $post->title }}" required></label>
                <label>Slug<input type="text" name="slug" value="{{ $post->slug }}" required></label>
                <label>Excerpt<textarea name="excerpt" rows="2">{{ $post->excerpt }}</textarea></label>
                <label>Content<textarea name="content" rows="4">{{ $post->content }}</textarea></label>
                <label>Cover Image URL<input type="text" name="cover_image" value="{{ $post->cover_image }}"></label>
                <label>Status
                    <select name="status"><option value="draft" @selected($post->status === 'draft')>draft</option><option value="published" @selected($post->status === 'published')>published</option></select>
                </label>
                <label>Published At<input type="datetime-local" name="published_at" value="{{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('Y-m-d\\TH:i') : '' }}"></label>
                <button type="submit">Update</button>
            </form>
            <form method="POST" action="{{ route('super-admin.content.destroy', ['module' => 'blog', 'id' => $post->id]) }}" onsubmit="return confirm('Hapus post ini?');">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>
        @endforeach
    </section>
@endif

@if ($moduleKey === 'gallery')
    <section class="card" id="form-create-gallery" style="display: @if($errors->any()) block @else none @endif;" data-searchable>
        <h3>Tambah Gallery Baru</h3>
        <form class="module-form module-form-grid" method="POST" action="{{ route('super-admin.content.store', 'gallery') }}">
            @csrf
            <label>Title<input type="text" name="title" required></label>
            <label>Category<input type="text" name="category"></label>
            <label>Type<select name="type"><option value="photo">photo</option><option value="video">video</option></select></label>
            <label>File Path<input type="text" name="file_path" required></label>
            <div class="form-actions">
                <button type="submit">Simpan Gallery</button>
                <button type="reset" class="btn-secondary">Cancel</button>
            </div>
        </form>
    </section>
    <section class="card" data-searchable>
        <div class="card-header-flex">
            <h3>Daftar Gallery</h3>
            <button type="button" class="btn-add-student" onclick="const form = document.getElementById('form-create-gallery'); if(form) form.style.display = form.style.display === 'none' ? 'block' : 'none';">
                <i data-lucide="plus-circle"></i>
                Tambah Gallery Baru
            </button>
        </div>
        @foreach($galleriesForManagement as $gallery)
            <form class="module-form" method="POST" action="{{ route('super-admin.content.update', ['module' => 'gallery', 'id' => $gallery->id]) }}">
                @csrf
                @method('PUT')
                <label>Title<input type="text" name="title" value="{{ $gallery->title }}" required></label>
                <label>Category<input type="text" name="category" value="{{ $gallery->category }}"></label>
                <label>Type<select name="type"><option value="photo" @selected($gallery->type === 'photo')>photo</option><option value="video" @selected($gallery->type === 'video')>video</option></select></label>
                <label>File Path<input type="text" name="file_path" value="{{ $gallery->file_path }}" required></label>
                <button type="submit">Update</button>
            </form>
            <form method="POST" action="{{ route('super-admin.content.destroy', ['module' => 'gallery', 'id' => $gallery->id]) }}" onsubmit="return confirm('Hapus gallery ini?');">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>
        @endforeach
    </section>
@endif

@if ($moduleKey === 'events')
    <section class="card" id="form-create-event" style="display: @if($errors->any()) block @else none @endif;" data-searchable>
        <h3>Tambah Event Baru</h3>
        <form class="module-form module-form-grid" method="POST" action="{{ route('super-admin.content.store', 'events') }}">
            @csrf
            <label>Title<input type="text" name="title" required></label>
            <label>Description<textarea name="description" rows="3"></textarea></label>
            <label>Date<input type="date" name="event_date"></label>
            <label>Location<input type="text" name="location"></label>
            <label>Status<select name="status"><option value="draft">draft</option><option value="upcoming">upcoming</option><option value="completed">completed</option><option value="cancelled">cancelled</option></select></label>
            <div class="form-actions">
                <button type="submit">Simpan Event</button>
                <button type="reset" class="btn-secondary">Cancel</button>
            </div>
        </form>
    </section>
    <section class="card" data-searchable>
        <div class="card-header-flex">
            <h3>Daftar Event</h3>
            <button type="button" class="btn-add-student" onclick="const form = document.getElementById('form-create-event'); if(form) form.style.display = form.style.display === 'none' ? 'block' : 'none';">
                <i data-lucide="calendar-plus"></i>
                Tambah Event Baru
            </button>
        </div>
        @foreach($eventsForManagement as $event)
            <form class="module-form" method="POST" action="{{ route('super-admin.content.update', ['module' => 'events', 'id' => $event->id]) }}">
                @csrf
                @method('PUT')
                <label>Title<input type="text" name="title" value="{{ $event->title }}" required></label>
                <label>Description<textarea name="description" rows="3">{{ $event->description }}</textarea></label>
                <label>Date<input type="date" name="event_date" value="{{ $event->event_date }}"></label>
                <label>Location<input type="text" name="location" value="{{ $event->location }}"></label>
                <label>Status<select name="status"><option value="draft" @selected($event->status === 'draft')>draft</option><option value="upcoming" @selected($event->status === 'upcoming')>upcoming</option><option value="completed" @selected($event->status === 'completed')>completed</option><option value="cancelled" @selected($event->status === 'cancelled')>cancelled</option></select></label>
                <button type="submit">Update</button>
            </form>
            <form method="POST" action="{{ route('super-admin.content.destroy', ['module' => 'events', 'id' => $event->id]) }}" onsubmit="return confirm('Hapus event ini?');">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>
        @endforeach
    </section>
@endif

@if ($moduleKey === 'testimonials')
    <section class="card" id="form-create-testimonial" style="display: @if($errors->any()) block @else none @endif;" data-searchable>
        <h3>Tambah Testimonial Baru</h3>
        <form class="module-form module-form-grid" method="POST" action="{{ route('super-admin.content.store', 'testimonials') }}">
            @csrf
            <label>Name<input type="text" name="name" required></label>
            <label>Role<input type="text" name="role"></label>
            <label>Message<textarea name="message" rows="3" required></textarea></label>
            <label>Publish<select name="is_published"><option value="1">Ya</option><option value="0">Tidak</option></select></label>
            <div class="form-actions">
                <button type="submit">Simpan Testimonial</button>
                <button type="reset" class="btn-secondary">Cancel</button>
            </div>
        </form>
    </section>
    <section class="card" data-searchable>
        <div class="card-header-flex">
            <h3>Daftar Testimonial</h3>
            <button type="button" class="btn-add-student" onclick="const form = document.getElementById('form-create-testimonial'); if(form) form.style.display = form.style.display === 'none' ? 'block' : 'none';">
                <i data-lucide="plus-circle"></i>
                Tambah Testimonial Baru
            </button>
        </div>
        @foreach($testimonialsForManagement as $testimonial)
            <form class="module-form" method="POST" action="{{ route('super-admin.content.update', ['module' => 'testimonials', 'id' => $testimonial->id]) }}">
                @csrf
                @method('PUT')
                <label>Name<input type="text" name="name" value="{{ $testimonial->name }}" required></label>
                <label>Role<input type="text" name="role" value="{{ $testimonial->role }}"></label>
                <label>Message<textarea name="message" rows="3" required>{{ $testimonial->message }}</textarea></label>
                <label>Publish<select name="is_published"><option value="1" @selected($testimonial->is_published)>Ya</option><option value="0" @selected(! $testimonial->is_published)>Tidak</option></select></label>
                <button type="submit">Update</button>
            </form>
            <form method="POST" action="{{ route('super-admin.content.destroy', ['module' => 'testimonials', 'id' => $testimonial->id]) }}" onsubmit="return confirm('Hapus testimonial ini?');">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>
        @endforeach
    </section>
@endif

@if ($moduleKey === 'settings')
    <section class="card" id="form-create-setting" style="display: @if($errors->any()) block @else none @endif;" data-searchable>
        <h3>Tambah Setting Baru</h3>
        <form class="module-form module-form-grid" method="POST" action="{{ route('super-admin.content.store', 'settings') }}">
            @csrf
            <label>Key<input type="text" name="key" required></label>
            <label>Value<textarea name="value" rows="2"></textarea></label>
            <div class="form-actions">
                <button type="submit">Simpan Setting</button>
                <button type="reset" class="btn-secondary">Cancel</button>
            </div>
        </form>
    </section>
    <section class="card" data-searchable>
        <div class="card-header-flex">
            <h3>Daftar Setting</h3>
            <button type="button" class="btn-add-student" onclick="const form = document.getElementById('form-create-setting'); if(form) form.style.display = form.style.display === 'none' ? 'block' : 'none';">
                <i data-lucide="plus-circle"></i>
                Tambah Setting Baru
            </button>
        </div>
        @foreach($settingsForManagement as $setting)
            <form class="module-form" method="POST" action="{{ route('super-admin.content.update', ['module' => 'settings', 'id' => $setting->id]) }}">
                @csrf
                @method('PUT')
                <label>Key<input type="text" name="key" value="{{ $setting->key }}" required></label>
                <label>Value<textarea name="value" rows="2">{{ $setting->value }}</textarea></label>
                <button type="submit">Update</button>
            </form>
            <form method="POST" action="{{ route('super-admin.content.destroy', ['module' => 'settings', 'id' => $setting->id]) }}" onsubmit="return confirm('Hapus setting ini?');">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>
        @endforeach
    </section>
@endif

@if ($moduleKey === 'logs')
    <section class="card" data-searchable>
        <h3>System Logs</h3>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>User ID</th>
                        <th>Module</th>
                        <th>Action</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logsForManagement as $log)
                        <tr>
                            <td>{{ $log->created_at }}</td>
                            <td>{{ $log->user_id ?? '-' }}</td>
                            <td>{{ $log->module ?? '-' }}</td>
                            <td>{{ $log->action }}</td>
                            <td>
                                <form method="POST" action="{{ route('super-admin.logs.destroy', $log->id) }}" onsubmit="return confirm('Hapus log ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No activity logs yet. System events will be listed once users start actions.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endif

@if ($moduleKey === 'reschedule')
    <section class="card" data-searchable>
        <h3>Reschedule Requests</h3>
        <p class="ui-card-subtitle">Daftar permintaan perubahan jadwal siswa yang memerlukan persetujuan.</p>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        @foreach ($columns as $column)
                            <th>{{ $column }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        @php $requestObj = $row[5]; @endphp
                        <tr>
                            <td>{{ $row[0] }}</td>
                            <td>{{ $row[1] }}</td>
                            <td>{{ $row[2] }}</td>
                            <td>{{ $row[3] }}</td>
                            <td>
                                @php
                                    $status = strtolower($requestObj->status);
                                    $type = $status === 'approved' ? 'success' : ($status === 'rejected' ? 'danger' : 'warning');
                                @endphp
                                <x-ui.badge :type="$type">{{ strtoupper($status) }}</x-ui.badge>
                            </td>
                            <td>
                                @if($status === 'pending')
                                    <div style="display:flex; gap:0.5rem;">
                                        <form action="{{ route('super-admin.reschedule.approve', $requestObj->id) }}" method="POST" onsubmit="return confirm('Approve reschedule ini?')">
                                            @csrf
                                            <button type="submit" class="btn-res-approve" title="Approve">
                                                <i data-lucide="check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('super-admin.reschedule.reject', $requestObj->id) }}" method="POST" onsubmit="return confirm('Reject reschedule ini?')">
                                            @csrf
                                            <button type="submit" class="btn-res-reject" title="Reject">
                                                <i data-lucide="x"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted">No Actions</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No reschedule requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <style>
        .btn-res-approve, .btn-res-reject {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: 0;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-res-approve { background: rgba(34, 197, 94, 0.15); color: #86efac; }
        .btn-res-approve:hover { background: #166534; color: #fff; }
        .btn-res-reject { background: rgba(239, 68, 68, 0.15); color: #fca5a5; }
        .btn-res-reject:hover { background: #991b1b; color: #fff; }
        .btn-res-approve i, .btn-res-reject i { width: 16px; height: 16px; }
    </style>
@endif

@if ($moduleKey === 'finance')
    <section class="stats-grid" data-searchable>
        <article class="card stat">
            <p>Total Invoice</p>
            <h2>{{ $financeSummary['total_invoice'] }}</h2>
        </article>
        <article class="card stat">
            <p>Pembayaran Berhasil</p>
            <h2>Rp{{ number_format($financeSummary['successful_payments'], 0, ',', '.') }}</h2>
        </article>
    </section>

    <section class="card" id="form-create-payment" style="display: @if($errors->any()) block @else none @endif;" data-searchable>
        <h3>Tambah Pembayaran</h3>
        <form class="module-form module-form-grid" method="POST" action="{{ route('super-admin.payments.store') }}">
            @csrf
            <label>Student
                <select name="student_id" required>
                    <option value="">Pilih student</option>
                    @foreach($studentsForFinance as $student)
                        <option value="{{ $student->id }}" @selected((string) old('student_id') === (string) $student->id)>{{ $student->name }}</option>
                    @endforeach
                </select>
            </label>
            <label>Class
                <select name="class_id">
                    <option value="">Tanpa class</option>
                    @foreach($classesForFinance as $class)
                        <option value="{{ $class->id }}" @selected((string) old('class_id') === (string) $class->id)>{{ $class->name }}</option>
                    @endforeach
                </select>
            </label>
            <label>Amount
                <input type="number" step="0.01" min="0" name="amount" value="{{ old('amount') }}" required>
            </label>
            <label>Status
                <select name="status" required>
                    <option value="paid" @selected(old('status', 'paid') === 'paid')>paid</option>
                    <option value="pending" @selected(old('status') === 'pending')>pending</option>
                </select>
            </label>
            <div class="form-actions">
                <button type="submit">Simpan Pembayaran</button>
                <button type="reset" class="btn-secondary">Cancel</button>
            </div>
        </form>
    </section>

    <section class="card" data-searchable>
        <div class="card-header-flex">
            <h3>Daftar Pembayaran</h3>
            <button type="button" class="btn-add-student" onclick="const form = document.getElementById('form-create-payment'); if(form) form.style.display = form.style.display === 'none' ? 'block' : 'none';">
                <i data-lucide="plus-circle"></i>
                Tambah Pembayaran
            </button>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Class</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paymentsForFinance as $payment)
                        <tr>
                            <td>{{ $payment->student?->name ?? '-' }}</td>
                            <td>{{ $payment->musicClass?->name ?? '-' }}</td>
                            <td>Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>
                                <x-ui.badge :type="$payment->status === 'paid' ? 'success' : 'warning'">
                                    {{ strtoupper($payment->status) }}
                                </x-ui.badge>
                            </td>
                            <td>{{ optional($payment->created_at)->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Belum ada data pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endif

@if ($moduleKey === 'schedule')
    {{-- Tailwind & Alpine.js CDN for immediate result --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        .font-saas { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>

    @php
        $scheduleFeatureReady = (bool) ($scheduleFeatureReady ?? false);
        $availableDayOptions = $dayOptions ?? ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
    @endphp

    <div 
        x-data="{ 
            studentModalOpen: false, 
            addModalOpen: false,
            studentData: {},
            showStudent(data) {
                this.studentData = data;
                this.studentModalOpen = true;
                if (window.lucide) {
                    setTimeout(() => window.lucide.createIcons(), 50);
                }
            }
        }"
        class="font-saas py-2 relative min-h-[600px]"
    >
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-5 gap-4 px-1">
            <div>
                <h1 class="text-xl font-bold text-gray-900 tracking-tight">Schedule Dashboard</h1>
                <p class="text-gray-400 text-[11px] font-medium tracking-wide uppercase">Music School Management System</p>
            </div>
            <div>
                <button @click="addModalOpen = true" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-[12px] font-bold rounded-lg transition-all shadow-md shadow-indigo-100 active:scale-95">
                    <i data-lucide="plus" class="w-3.5 h-3.5 mr-1.5"></i>
                    Add Schedule
                </button>
            </div>
        </div>

        {{-- Main Content --}}
        @php
            $nestedSchedules = [];
            foreach ($schedulesForManagement as $scheduleItem) {
                $className = $scheduleItem->musicClass?->name ?? 'Unassigned Class';
                $teacherName = $scheduleItem->teacher?->name ?? ($scheduleItem->musicClass?->teacher?->name ?? 'Belum ada pengajar');
                $day = $scheduleItem->day;
                if (!isset($nestedSchedules[$className])) $nestedSchedules[$className] = [];
                if (!isset($nestedSchedules[$className][$teacherName])) $nestedSchedules[$className][$teacherName] = [];
                if (!isset($nestedSchedules[$className][$teacherName][$day])) $nestedSchedules[$className][$teacherName][$day] = [];
                $nestedSchedules[$className][$teacherName][$day][] = $scheduleItem;
            }
        @endphp

        <div class="space-y-3">
            @forelse ($nestedSchedules as $className => $teachers)
                <div x-data="{ open: false }" class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                    {{-- Class Header --}}
                    <div @click="open = !open" class="px-5 py-3 bg-white flex items-center justify-between cursor-pointer group">
                        <div class="flex items-center gap-4">
                            <div class="w-9 h-9 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 ring-2 ring-indigo-50/30 transition-all">
                                <i data-lucide="music" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h2 class="text-[15px] font-bold text-gray-800 group-hover:text-indigo-600 transition-colors">Class: {{ $className }}</h2>
                                <p class="text-[10px] font-bold text-gray-400 tracking-wider uppercase">{{ count($teachers) }} Teachers</p>
                            </div>
                        </div>
                        <div class="w-7 h-7 rounded-full bg-gray-50 flex items-center justify-center">
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400 transform transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                        </div>
                    </div>

                    {{-- Class Content (Teachers) --}}
                    <div x-show="open" x-collapse x-cloak class="px-5 pb-3 space-y-3">
                        @foreach ($teachers as $teacherName => $days)
                            <div x-data="{ openTeacher: false }" class="bg-gray-50/50 rounded-xl border border-gray-100 overflow-hidden">
                                <div @click="openTeacher = !openTeacher" class="px-4 py-2.5 flex items-center justify-between cursor-pointer group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-7 h-7 rounded-full bg-white border border-indigo-100 flex items-center justify-center text-indigo-500">
                                            <i data-lucide="user" class="w-3.5 h-3.5"></i>
                                        </div>
                                        <h3 class="text-[13px] font-bold text-gray-700">Instructor: <span class="text-indigo-600 font-extrabold">{{ $teacherName }}</span></h3>
                                    </div>
                                    <i data-lucide="chevron-down" class="w-3 h-3 text-gray-400 transform transition-transform duration-200" :class="openTeacher ? 'rotate-180' : ''"></i>
                                </div>

                                {{-- Days Grid --}}
                                <div x-show="openTeacher" x-collapse x-cloak class="px-4 pb-4 pt-0">
                                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                                        @foreach ($days as $day => $slots)
                                            <div class="bg-white p-4 rounded-xl border border-gray-50 shadow-sm">
                                                <div class="flex items-center justify-between mb-3">
                                                    <div class="px-2 py-0.5 bg-indigo-600 text-white text-[8px] font-black rounded-md tracking-wider uppercase">{{ $day }}</div>
                                                    <span class="text-[8px] font-bold text-gray-300 uppercase">{{ count($slots) }} Slots</span>
                                                </div>
                                                
                                                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-2">
                                                    @foreach ($slots as $slot)
                                                        @php
                                                            $isBooked = (bool)$slot->student_id;
                                                            $timeLabel = substr((string)$slot->time, 0, 5);
                                                            $student = $slot->student;
                                                            $studentPayload = $isBooked ? [
                                                                'id' => $student?->id,
                                                                'name' => $student?->user?->name ?? ($student?->name ?? '-'),
                                                                'phone' => $student?->phone ?? '-',
                                                                'address' => $student?->address ?? '-',
                                                                'class_name' => $student->class?->name ?? $student->classes?->pluck('name')->implode(', ') ?? $className,
                                                                'teacher_name' => $teacherName
                                                            ] : null;
                                                        @endphp
                                                        <div x-data="{ showActions: false }" class="relative">
                                                            <button 
                                                                @click="
                                                                    if ({{ $isBooked ? 'true' : 'false' }}) {
                                                                        showStudent(@js($studentPayload))
                                                                    } else {
                                                                        showActions = !showActions
                                                                    }
                                                                "
                                                                class="w-full py-1.5 rounded-lg text-[10px] font-black transition-all border
                                                                    {{ $isBooked 
                                                                        ? 'bg-indigo-600 border-indigo-600 text-white hover:bg-indigo-700 hover:scale-105 shadow-md shadow-indigo-50' 
                                                                        : 'bg-white border-gray-50 text-gray-500 hover:border-indigo-500 hover:text-indigo-600 hover:bg-indigo-50/30' }}"
                                                            >
                                                                {{ $timeLabel }}
                                                                @if($isBooked)
                                                                    <div class="text-[6px] opacity-80 font-black">FULL</div>
                                                                @endif
                                                            </button>

                                                            @if(!$isBooked)
                                                            <div x-show="showActions" @click.away="showActions = false" x-transition.opacity class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-28 bg-gray-900 rounded-lg shadow-xl z-50 p-1 border border-white/10">
                                                                <form method="POST" action="{{ route('super-admin.schedule.destroy', $slot) }}" onsubmit="return confirm('Hapus?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="w-full flex items-center justify-center px-2 py-1.5 text-[9px] font-bold text-white hover:bg-red-500 rounded-md transition-all">
                                                                        <i data-lucide="trash-2" class="w-2.5 h-2.5 mr-1.5"></i> Delete
                                                                    </button>
                                                                </form>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-20 bg-white border-2 border-dashed border-gray-100 rounded-3xl">
                    <i data-lucide="calendar-off" class="w-8 h-8 text-gray-200 mx-auto mb-4"></i>
                    <h3 class="text-base font-bold text-gray-800">No Schedules</h3>
                </div>
            @endforelse
        </div>

        {{-- Localized Modals (Inside relative container) --}}
        <div>
            {{-- Modal for Create Schedule --}}
            <div x-show="addModalOpen" x-cloak 
                class="absolute inset-0 z-[100] flex items-center justify-center"
                style="background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(4px);">
                
                <div x-show="addModalOpen" 
                    x-transition:enter="ease-out duration-200" 
                    x-transition:enter-start="opacity-0 scale-95" 
                    x-transition:enter-end="opacity-100 scale-100" 
                    @click.away="addModalOpen = false"
                    class="relative bg-white rounded-3xl shadow-[0_25px_60px_-15px_rgba(0,0,0,0.15)] w-full max-w-[320px] border border-gray-100/50 overflow-hidden transform -translate-y-5">
                    
                    <form method="POST" action="{{ route('super-admin.schedule.store') }}" class="p-6">
                        @csrf
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="text-[14px] font-bold text-gray-900 tracking-tight">New Schedule</h3>
                            <button type="button" @click="addModalOpen = false" class="text-gray-300 hover:text-indigo-600 transition-colors">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Class</label>
                                <select name="class_id" class="w-full h-10 px-3 bg-white border border-gray-200 rounded-lg focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-[12px] font-bold text-gray-700 transition-all">
                                    @foreach($classesForSchedule as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }} ({{ $class->teacher?->name ?? 'No Primary Teacher' }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Assign to Teacher (Optional)</label>
                                <select name="teacher_id" class="w-full h-10 px-3 bg-white border border-gray-200 rounded-lg focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-[12px] font-bold text-gray-700 transition-all">
                                    <option value="">Use Class Primary Teacher</option>
                                    @foreach($teachersForClassOptions as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                                <p class="text-[8px] text-gray-400 mt-1 italic">Jika dikosongkan, akan menggunakan guru utama kelas.</p>
                            </div>

                            <div>
                                <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-2.5">Repeat Days</label>
                                <div class="grid grid-cols-4 gap-2">
                                    @foreach($availableDayOptions as $dayOption)
                                        <label class="flex items-center gap-1.5 cursor-pointer group">
                                            <input type="checkbox" name="days[]" value="{{ $dayOption }}" class="w-3 h-3 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            <span class="text-[10px] font-bold text-gray-400 group-hover:text-indigo-600">{{ substr($dayOption, 0, 3) }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Start Time</label>
                                    <input type="time" name="start_time" class="w-full h-9 px-3 bg-white border border-gray-200 rounded-lg focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-[12px] font-bold text-gray-700 transition-all">
                                </div>
                                <div>
                                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">End Time</label>
                                    <input type="time" name="end_time" class="w-full h-9 px-3 bg-white border border-gray-200 rounded-lg focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-[12px] font-bold text-gray-700 transition-all">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Interval (Mins)</label>
                                <input type="number" name="interval" value="60" class="w-full h-9 px-3 bg-white border border-gray-200 rounded-lg focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-[12px] font-bold text-gray-700 transition-all">
                            </div>
                        </div>

                        <div class="mt-8">
                            <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-[11px] transition-all shadow-lg shadow-indigo-100 active:scale-95">
                                Create Schedule
                            </button>
                            <button type="button" @click="addModalOpen = false" class="w-full mt-2 py-2 text-[10px] font-bold text-gray-400 hover:text-gray-600 text-center transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Student Detail Modal --}}
            <div x-show="studentModalOpen" x-cloak 
                class="absolute inset-0 z-[100] flex items-center justify-center"
                style="background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(4px);">
                
                <div x-show="studentModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative bg-white rounded-[2rem] text-left overflow-hidden shadow-[0_25px_60px_-15px_rgba(0,0,0,0.15)] transform transition-all w-full max-w-[320px] border border-gray-100 -translate-y-5">
                    <div class="px-6 py-8">
                        <button @click="studentModalOpen = false" class="absolute top-5 right-5 text-gray-300 hover:text-indigo-500 transition-colors">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>

                        <div class="flex flex-col items-center mb-6">
                            <div class="w-16 h-16 rounded-2xl bg-indigo-600 flex items-center justify-center text-white text-xl font-bold mb-4 shadow-lg shadow-indigo-100">
                                <span x-text="studentData.name ? studentData.name.charAt(0).toUpperCase() : '?'"></span>
                            </div>
                            <h3 class="text-base font-bold text-gray-900 text-center" x-text="studentData.name || '-'"></h3>
                            <p class="text-[9px] font-bold text-indigo-500 mt-1 uppercase tracking-widest" x-text="'ID: #' + (studentData.id || '00')"></p>
                        </div>

                        <div class="space-y-4 px-2">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-medium text-gray-400">Class Unit</span>
                                <span class="text-[11px] font-bold text-gray-800" x-text="studentData.class_name || '-'"></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-medium text-gray-400">Instructor</span>
                                <span class="text-[11px] font-bold text-gray-800" x-text="studentData.teacher_name || '-'"></span>
                            </div>
                            
                            <div class="pt-2 space-y-4">
                                <div class="flex items-start gap-4">
                                    <i data-lucide="phone" class="w-3.5 h-3.5 text-indigo-500 shrink-0 mt-0.5"></i>
                                    <div>
                                        <p class="text-[11px] font-bold text-gray-800" x-text="studentData.phone || '-'"></p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-indigo-500 shrink-0 mt-0.5"></i>
                                    <div>
                                        <p class="text-[11px] font-bold text-gray-800 leading-relaxed" x-text="studentData.address || '-'"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8">
                            <button @click="studentModalOpen = false" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-[11px] transition-all active:scale-95">
                                Close Profile
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Re-initialize Lucide icons for the redesign
        if (window.lucide) {
            window.lucide.createIcons();
        }
    </script>
@endif

    {{-- Final Cleanup: Legacy Schedule Code Removed --}}

@if (! in_array($moduleKey, ['users', 'roles', 'teachers', 'schedule', 'classes', 'students', 'registrations', 'reschedule', 'finance', 'blog', 'gallery', 'events', 'testimonials', 'settings', 'logs'], true))
<section class="card" data-searchable>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    @foreach ($columns as $column)
                        <th>{{ $column }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse ($rows as $row)
                    <tr>
                        @foreach ($row as $value)
                            <td>{{ $value }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columns) }}">No records available for this module yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const syncBodyModalState = () => {
        const hasOpenPopover = document.querySelector('details.action-popover[open]') !== null;
        document.body.classList.toggle('modal-open', hasOpenPopover);
    };

    // Close action-popover modal via X button
    document.querySelectorAll('.action-popover-close').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            const details = btn.closest('details.action-popover');
            if (details) details.removeAttribute('open');
            syncBodyModalState();
        });
    });

    // Teacher Modal Controls
    window.openTeacherModal = function() {
        const modal = document.getElementById('modal-create-teacher');
        if (modal) {
            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('active'), 10);
            document.body.style.overflow = 'hidden';
            if (window.lucide) window.lucide.createIcons();
        }
    };

    window.closeTeacherModal = function() {
        const modal = document.getElementById('modal-create-teacher');
        if (modal) {
            modal.classList.remove('active');
            setTimeout(() => {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }, 400);
        }
    };

    // Image Preview Utility
    window.previewImage = function(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
            };
            reader.readAsDataURL(input.files[0]);
        }
    };

    // Form Loading State
    const teacherForm = document.getElementById('form-create-teacher-modal');
    if (teacherForm) {
        teacherForm.addEventListener('submit', function() {
            const btn = document.getElementById('btn-submit-teacher');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i data-lucide="loader-2" class="animate-spin"></i> Mohon Tunggu...';
                if (window.lucide) window.lucide.createIcons();
            }
        });
    }

    // Close action-popover when clicking the backdrop (the ::before pseudo-element area)
    document.addEventListener('click', e => {
        // If the click target is a summary inside an open action-popover, do nothing (native toggle)
        if (e.target.closest('details.action-popover')) return;
        // Otherwise close all open action-popovers
        document.querySelectorAll('details.action-popover[open]').forEach(d => {
            d.removeAttribute('open');
        });
        syncBodyModalState();
    });

    // Prevent clicks inside the modal form from bubbling and closing the modal
    document.querySelectorAll('.action-popover-form').forEach(form => {
        form.addEventListener('click', e => e.stopPropagation());
    });

    document.querySelectorAll('details.action-popover').forEach(details => {
        details.addEventListener('toggle', syncBodyModalState);
    });
    syncBodyModalState();

    // Teacher edit modal: inline validation + loading state on submit
    document.querySelectorAll('.teacher-edit-modal').forEach(form => {
        const fields = form.querySelectorAll('input, select, textarea');
        const submitBtn = form.querySelector('.te-btn--primary');

        const getMessage = (field) => {
            if (!field.validity) return 'Input tidak valid.';
            if (field.validity.valueMissing) return 'Field ini wajib diisi.';
            if (field.validity.typeMismatch && field.type === 'email') return 'Masukkan format email yang valid.';
            if (field.validity.tooShort) return 'Input terlalu pendek.';
            if (field.validity.tooLong) return 'Input terlalu panjang.';
            if (field.validity.patternMismatch) return 'Format input tidak sesuai.';
            return 'Periksa kembali input ini.';
        };

        const setFieldError = (field, message) => {
            const wrapper = field.closest('.te-field');
            if (!wrapper) return;
            wrapper.classList.add('has-error');
            field.setAttribute('aria-invalid', 'true');
            const error = wrapper.querySelector('.te-error-text');
            if (error) error.textContent = message;
        };

        const clearFieldError = (field) => {
            const wrapper = field.closest('.te-field');
            if (!wrapper) return;
            wrapper.classList.remove('has-error');
            field.removeAttribute('aria-invalid');
            const error = wrapper.querySelector('.te-error-text');
            if (error) error.textContent = '';
        };

        const validateField = (field) => {
            clearFieldError(field);
            if (field.checkValidity()) return true;
            setFieldError(field, getMessage(field));
            return false;
        };

        fields.forEach(field => {
            field.addEventListener('input', () => validateField(field));
            field.addEventListener('change', () => validateField(field));
            field.addEventListener('blur', () => validateField(field));
        });

        form.addEventListener('submit', (event) => {
            let firstInvalid = null;
            let allValid = true;

            fields.forEach(field => {
                const isValid = validateField(field);
                if (!isValid) {
                    allValid = false;
                    if (!firstInvalid) firstInvalid = field;
                }
            });

            if (!allValid) {
                event.preventDefault();
                if (submitBtn) submitBtn.classList.remove('is-loading');
                if (firstInvalid) firstInvalid.focus();
                return;
            }

            if (submitBtn) {
                submitBtn.classList.add('is-loading');
                submitBtn.setAttribute('aria-busy', 'true');
                submitBtn.disabled = true;
            }
        });
    });

    // Re-initialize Lucide icons when popovers open
    document.querySelectorAll('details.action-popover').forEach(details => {
        details.addEventListener('toggle', () => {
            if (details.open && window.lucide) {
                window.lucide.createIcons();
            }
        });
    });

    // Toast Notification System
    window.showToast = function(type, title, message) {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = `toast-item ${type}`;
        toast.innerHTML = `
            <div class="toast-icon">
                <i data-lucide="${type === 'success' ? 'check-circle' : 'alert-circle'}"></i>
            </div>
            <div class="toast-content">
                <h4>${title}</h4>
                <p>${message}</p>
            </div>
        `;
        container.appendChild(toast);
        if (window.lucide) window.lucide.createIcons();

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => toast.remove(), 400);
        }, 5000);
    };

    // Trigger toasts from session
    @if(session('success'))
        showToast('success', 'Berhasil!', '{{ session('success') }}');
    @endif
    @if(session('error'))
        showToast('error', 'Gagal!', '{{ session('error') }}');
    @endif
    @if($errors->any())
        showToast('error', 'Validasi Gagal', 'Silakan periksa kembali inputan Anda.');
    @endif
});

// Dynamic Schedule Loader for Admin Create Student Modal
async function loadAdminSchedules(classId) {
    const container = document.getElementById('admin-schedule-container');
    const preview = document.getElementById('admin-selected-preview');
    const tags = document.getElementById('admin-selected-tags');
    
    if (!classId) {
        container.innerHTML = '<p style="padding: 1.5rem; color: #64748b; font-size: 0.9rem; font-style: italic; text-align: center;">Silakan pilih instrumen terlebih dahulu.</p>';
        preview.style.display = 'none';
        return;
    }

    container.innerHTML = '<p style="padding: 1.5rem; color: #64748b; font-size: 0.9rem; text-align: center;">Memuat jadwal...</p>';

    try {
        const response = await fetch(`/schedules/by-class/${classId}`);
        const data = await response.json();
        const grouped = data.grouped || {};

        if (Object.keys(grouped).length === 0) {
            container.innerHTML = '<p style="padding: 1.5rem; color: #ef4444; font-size: 0.9rem; text-align: center;">Tidak ada jadwal tersedia untuk instrumen ini.</p>';
            return;
        }

        let html = '<div class="admin-schedule-accordion">';
        let index = 0;
        for (const day in grouped) {
            const isActive = index === 0 ? 'is-active' : '';
            html += `
                <div class="admin-accordion-item ${isActive}" style="border-bottom: 1px solid #e2e8f0;">
                    <button type="button" onclick="this.parentElement.classList.toggle('is-active')" style="width: 100%; display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.25rem; background: #fff; border: 0; cursor: pointer; text-align: left;">
                        <span style="font-weight: 700; color: #1e293b;">${day}</span>
                        <i data-lucide="chevron-down" style="width: 1.25rem; height: 1.25rem; transition: transform 0.2s;"></i>
                    </button>
                    <div class="admin-accordion-content" style="display: none; padding: 0.75rem 1rem 1.25rem; background: #f8fafc;">
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 0.5rem;">
                            ${grouped[day].map(s => {
                                const isBooked = String(s.status).toLowerCase() === 'booked';
                                return `
                                    <label style="position: relative; display: flex; flex-direction: column; align-items: center; padding: 0.75rem 0.5rem; border: 1.5px solid ${isBooked ? '#e2e8f0' : '#e2e8f0'}; border-radius: 0.75rem; background: ${isBooked ? '#f1f5f9' : '#fff'}; cursor: ${isBooked ? 'not-allowed' : 'pointer'}; transition: all 0.2s; opacity: ${isBooked ? '0.6' : '1'};">
                                        <input type="radio" name="schedule_id" value="${s.id}" data-label="${day} ${s.time}" ${isBooked ? 'disabled' : ''} onchange="updateAdminSelectedPreview(this)" style="position: absolute; opacity: 0; inset: 0;">
                                        <span style="font-size: 0.9rem; font-weight: 700; color: #334155;">${s.time}</span>
                                        ${isBooked ? '<span style="font-size: 0.7rem; color: #ef4444; font-weight: 700;">Full</span>' : ''}
                                    </label>
                                `;
                            }).join('')}
                        </div>
                    </div>
                </div>
            `;
            index++;
        }
        html += '</div>';
        container.innerHTML = html;
        if (window.lucide) window.lucide.createIcons();

        // Style for accordion active state
        const style = document.createElement('style');
        style.textContent = `
            .admin-accordion-item.is-active .admin-accordion-content { display: block !important; }
            .admin-accordion-item.is-active i[data-lucide="chevron-down"] { transform: rotate(180deg); }
            label:has(input[type="radio"]:checked) { border-color: #6366f1 !important; background: #eff6ff !important; box-shadow: 0 0 0 1px #6366f1; }
        `;
        document.head.appendChild(style);

    } catch (error) {
        console.error(error);
        container.innerHTML = '<p style="padding: 1.5rem; color: #ef4444; font-size: 0.9rem; text-align: center;">Gagal memuat jadwal.</p>';
    }
}

function updateAdminSelectedPreview(radio) {
    const preview = document.getElementById('admin-selected-preview');
    const tags = document.getElementById('admin-selected-tags');
    
    if (radio.checked) {
        preview.style.display = 'block';
        tags.innerHTML = `
            <span style="display: inline-flex; align-items: center; gap: 0.5rem; background: #6366f1; color: #fff; padding: 0.4rem 0.8rem; border-radius: 999px; font-size: 0.85rem; font-weight: 600;">
                ${radio.dataset.label}
                <i data-lucide="x" onclick="document.querySelector('input[name=\\'schedule_id\\'][value=\\'${radio.value}\\']').checked = false; updateAdminSelectedPreview({checked:false});" style="width: 14px; height: 14px; cursor: pointer;"></i>
            </span>
        `;
        if (window.lucide) window.lucide.createIcons();
    } else {
        preview.style.display = 'none';
        tags.innerHTML = '';
    }
}

window.toggleFavoriteSong = function(select, targetId) {
    const target = document.getElementById(targetId);
    if (!target) return;
    
    if (select.value === 'Vocal') {
        target.style.display = 'block';
    } else {
        target.style.display = 'none';
        const input = target.querySelector('input');
        if (input) input.value = '';
    }
}
</script>
@endpush

@push('modals')
<div id="modal-create-student" class="premium-form-card">
    <div class="premium-form-container">
        <button type="button" class="premium-modal-close" onclick="document.getElementById('modal-create-student').style.display = 'none';">
            <i data-lucide="x"></i>
        </button>

        <div class="premium-form-header">
            <div class="premium-form-icon">
                <i data-lucide="user-plus"></i>
            </div>
            <div class="premium-form-title">
                <h2>Tambah Siswa Baru</h2>
                <p>Daftarkan siswa baru ke dalam sistem manajemen ROFC secara manual.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('super-admin.students.store') }}">
            @csrf
            
            {{-- STEP 1: DATA SISWA --}}
            <div class="premium-form-group">
                <div class="premium-form-group-title">
                    <span><i data-lucide="user" style="width: 1rem; height: 1rem;"></i></span>
                    <h3>Informasi Dasar Siswa</h3>
                </div>
                <div class="premium-form-grid">
                    <div class="premium-field">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name" class="premium-input" value="{{ old('name') }}" placeholder="Nama sesuai identitas" required>
                    </div>
                    <div class="premium-field">
                        <label for="nama_panggilan">Nama Panggilan</label>
                        <input type="text" id="nama_panggilan" name="nama_panggilan" class="premium-input" value="{{ old('nama_panggilan') }}" placeholder="Nama panggilan">
                    </div>
                    <div class="premium-field">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select id="jenis_kelamin" name="jenis_kelamin" class="premium-select">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="laki-laki" @selected(old('jenis_kelamin') === 'laki-laki')>Laki-laki</option>
                            <option value="perempuan" @selected(old('jenis_kelamin') === 'perempuan')>Perempuan</option>
                        </select>
                    </div>
                    <div class="premium-field">
                        <label for="age">Umur (Tahun)</label>
                        <input type="number" id="age" name="age" class="premium-input" min="4" max="80" value="{{ old('age') }}" placeholder="Contoh: 12">
                    </div>
                    <div class="premium-field">
                        <label for="tempat_lahir">Tempat Lahir</label>
                        <input type="text" id="tempat_lahir" name="tempat_lahir" class="premium-input" value="{{ old('tempat_lahir') }}" placeholder="Kota kelahiran">
                    </div>
                    <div class="premium-field">
                        <label for="tanggal_lahir">Tanggal Lahir</label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="premium-input" value="{{ old('tanggal_lahir') }}">
                    </div>
                    <div class="premium-field">
                        <label for="kewarganegaraan">Kewarganegaraan</label>
                        <input type="text" id="kewarganegaraan" name="kewarganegaraan" class="premium-input" value="{{ old('kewarganegaraan', 'Indonesia') }}">
                    </div>
                    <div class="premium-field">
                        <label for="email">Email Siswa</label>
                        <input type="email" id="email" name="email" class="premium-input" value="{{ old('email') }}" placeholder="siswa@email.com">
                    </div>
                    <div class="premium-field">
                        <label for="phone">No. HP Siswa</label>
                        <input type="text" id="phone" name="phone" class="premium-input" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="premium-field">
                        <label for="ig_siswa">Instagram Siswa</label>
                        <input type="text" id="ig_siswa" name="ig_siswa" class="premium-input" value="{{ old('ig_siswa') }}" placeholder="@username">
                    </div>
                    <div class="premium-field full-width">
                        <label for="address">Alamat Domisili</label>
                        <textarea id="address" name="address" class="premium-textarea" placeholder="Alamat lengkap tempat tinggal">{{ old('address') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- STEP 2: DATA ORANG TUA --}}
            <div class="premium-form-group">
                <div class="premium-form-group-title">
                    <span><i data-lucide="users" style="width: 1rem; height: 1rem;"></i></span>
                    <h3>Data Orang Tua / Wali</h3>
                </div>
                <div class="premium-form-grid">
                    <div class="premium-field">
                        <label for="nama_ortu">Nama Orang Tua</label>
                        <input type="text" id="nama_ortu" name="nama_ortu" class="premium-input" value="{{ old('nama_ortu') }}" placeholder="Nama Ayah / Ibu / Wali">
                    </div>
                    <div class="premium-field">
                        <label for="pekerjaan_ortu">Pekerjaan</label>
                        <input type="text" id="pekerjaan_ortu" name="pekerjaan_ortu" class="premium-input" value="{{ old('pekerjaan_ortu') }}" placeholder="Pekerjaan Orang Tua">
                    </div>
                    <div class="premium-field">
                        <label for="no_hp_ortu">No. HP Orang Tua</label>
                        <input type="text" id="no_hp_ortu" name="no_hp_ortu" class="premium-input" value="{{ old('no_hp_ortu') }}" placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="premium-field">
                        <label for="email_ortu">Email Orang Tua</label>
                        <input type="email" id="email_ortu" name="email_ortu" class="premium-input" value="{{ old('email_ortu') }}" placeholder="ortu@email.com">
                    </div>
                    <div class="premium-field">
                        <label for="ig_ortu">Instagram Orang Tua</label>
                        <input type="text" id="ig_ortu" name="ig_ortu" class="premium-input" value="{{ old('ig_ortu') }}" placeholder="@username_ortu">
                    </div>
                </div>
            </div>

            {{-- STEP 3: PROGRAM & JADWAL (Matching Register Form) --}}
            <div class="premium-form-group">
                <div class="premium-form-group-title">
                    <span><i data-lucide="music" style="width: 1rem; height: 1rem;"></i></span>
                    <h3>Program dan Jadwal</h3>
                    <p style="margin: 0; font-size: 0.8rem; color: #64748b; font-weight: 400;">Pilih instrumen dan jadwal yang tersedia.</p>
                </div>
                <div class="premium-form-grid">
                    <div class="premium-field">
                        <label for="admin_class_id">Instrumen</label>
                        <select id="admin_class_id" name="class_id" class="premium-select" required onchange="loadAdminSchedules(this.value);">
                            <option value="">Pilih Instrumen</option>
                            @foreach($classesForManagement as $classItem)
                                <option value="{{ $classItem->id }}" @selected(old('class_id') == $classItem->id)>
                                    {{ $classItem->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="premium-field">
                        <label for="start_date">Tanggal Mulai Belajar</label>
                        <input type="date" id="start_date" name="start_date" class="premium-input" value="{{ old('start_date', date('Y-m-d')) }}">
                    </div>

                    <div class="premium-field">
                        <label for="duration_months">Durasi Belajar</label>
                        <select id="duration_months" name="duration_months" class="premium-select">
                            <option value="1" @selected(old('duration_months') == 1)>1 Bulan</option>
                            <option value="2" @selected(old('duration_months') == 2)>2 Bulan</option>
                            <option value="3" @selected(old('duration_months') == 3)>3 Bulan</option>
                            <option value="6" @selected(old('duration_months') == 6)>6 Bulan</option>
                            <option value="12" @selected(old('duration_months') == 12)>1 Tahun</option>
                        </select>
                    </div>

                    <div class="premium-field full-width">
                        <label>Pilih Jadwal</label>
                        <div style="margin-top: 0.5rem;">
                            <!-- Selected Preview -->
                            <div id="admin-selected-preview" style="display: none; margin-bottom: 1rem; padding: 0.75rem; background: #f0f7ff; border: 1px dashed #6366f1; border-radius: 0.75rem;">
                                <span style="font-size: 0.75rem; font-weight: 700; color: #6366f1; text-transform: uppercase; display: block; margin-bottom: 0.25rem;">Jadwal Terpilih:</span>
                                <div id="admin-selected-tags" style="display: flex; flex-wrap: wrap; gap: 0.5rem;"></div>
                            </div>

                            <!-- Schedule Container -->
                            <div id="admin-schedule-container" style="border: 1px solid #e2e8f0; border-radius: 1rem; overflow: hidden; background: #fff;">
                                <p style="padding: 1.5rem; color: #64748b; font-size: 0.9rem; font-style: italic; text-align: center;">Silakan pilih instrumen terlebih dahulu.</p>
                            </div>
                        </div>
                    </div>

                    <div class="premium-field full-width">
                        <label>Program Tambahan (opsional)</label>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 0.75rem; margin-top: 0.5rem;">
                            @php $oldProgramTambahan = old('program_tambahan', []); @endphp
                            @foreach (['Teori Musik', 'Ensemble / Band', 'Skill Teknik (ajang kompetisi)', 'Ujian Sertifikat bertaraf international'] as $prog)
                                <label style="display: flex; align-items: center; gap: 0.5rem; color: #475569; cursor: pointer; font-size: 0.9rem;">
                                    <input type="checkbox" name="program_tambahan[]" value="{{ $prog }}" @checked(in_array($prog, $oldProgramTambahan)) style="accent-color: #6366f1; width: 1.1rem; height: 1.1rem;">
                                    {{ $prog }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="premium-field">
                        <label for="pengalaman">Pernah belajar musik sebelumnya?</label>
                        <select id="pengalaman" name="pengalaman" class="premium-select">
                            <option value="0" @selected(old('pengalaman') == '0')>Belum Pernah</option>
                            <option value="1" @selected(old('pengalaman') == '1')>Sudah Pernah</option>
                        </select>
                    </div>

                    <div class="premium-field full-width">
                        <label for="deskripsi_pengalaman">Deskripsi Pengalaman</label>
                        <textarea id="deskripsi_pengalaman" name="deskripsi_pengalaman" class="premium-textarea" placeholder="Ceritakan pengalaman belajar musik sebelumnya (jika ada)">{{ old('deskripsi_pengalaman') }}</textarea>
                    </div>

                    <div class="premium-field" id="manual-student-favorite-song-group">
                        <label for="favorite_song">Lagu Favorite</label>
                        <input type="text" id="favorite_song" name="favorite_song" class="premium-input" value="{{ old('favorite_song') }}" placeholder="Contoh: Heal The World">
                    </div>

                    <div class="premium-field">
                        <label for="is_active">Status Siswa</label>
                        <select id="is_active" name="is_active" class="premium-select" required>
                            <option value="1" @selected(old('is_active', '1') === '1')>Active (Aktif Belajar)</option>
                            <option value="0" @selected(old('is_active') === '0')>Inactive (Cuti/Berhenti)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="premium-form-actions">
                <button type="button" class="btn-premium-secondary" onclick="document.getElementById('modal-create-student').style.display = 'none';">Batal</button>
                <button type="submit" class="btn-premium-primary">
                    <i data-lucide="save"></i>
                    Simpan Data & Buat Akun
                </button>
            </div>
        </form>
    </div>
</div>
@endpush



