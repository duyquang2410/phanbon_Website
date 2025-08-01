<style>
/* Timeline styles */
.timeline {
    margin: 0;
    padding: 0;
    list-style: none;
    position: relative;
}

.timeline:before {
    content: '';
    position: absolute;
    top: 0;
    left: 1rem;
    height: 100%;
    width: 2px;
    background: #e9ecef;
}

.timeline-block {
    position: relative;
    display: flex;
    align-items: flex-start;
}

.timeline-step {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-right: 1.5rem;
    z-index: 1;
    position: relative;
}

.timeline-step i {
    font-size: 1rem;
}

.timeline-content {
    flex: 1;
    position: relative;
    padding-bottom: 1.5rem;
}

/* Card styles */
.card-pricing {
    position: relative;
    z-index: 1;
    overflow: hidden;
}

.card-pricing:after {
    content: '';
    position: absolute;
    bottom: -50%;
    left: -1rem;
    width: 200%;
    height: 200%;
    transform: rotate(-8deg);
    background: rgba(255,255,255,0.1);
    z-index: -1;
}

/* Table styles */
.table > :not(caption) > * > * {
    padding: 1rem;
}

.table tbody tr:hover {
    background: rgba(233, 236, 239, 0.4);
}

/* Print styles */
@media print {
    .card {
        box-shadow: none !important;
    }

    .bg-gradient-primary {
        background: #5e72e4 !important;
        -webkit-print-color-adjust: exact;
    }

    .text-white {
        color: #fff !important;
        -webkit-print-color-adjust: exact;
    }
}

/* Search styles */
.search-wrapper {
    position: relative;
}

.search-wrapper .form-control {
    height: 45px;
    padding-left: 35px;
    padding-right: 15px;
    border: 1px solid #d2d6da;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    box-shadow: 0 0 2px rgba(0,0,0,0.05);
    transition: all 0.2s ease;
}

.search-wrapper .form-control:focus {
    border-color: #e91e63;
    box-shadow: 0 0 5px rgba(233, 30, 99, 0.2);
}

.search-wrapper i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #adb5bd;
    font-size: 0.875rem;
}
</style> 