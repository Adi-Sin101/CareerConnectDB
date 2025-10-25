<?php
if (!defined('DEVELOPMENT_MODE')) {
    include 'query_logger.php';
}

if (DEVELOPMENT_MODE) {
    $logs = get_query_logs();
?>
<!-- SQL Query Terminal Widget -->
<style>
/* Floating SQL Terminal Styles */
.sql-terminal {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 400px;
    height: 300px;
    background: rgba(0, 0, 0, 0.9);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    z-index: 9999;
    display: none;
    flex-direction: column;
    font-family: 'Courier New', monospace;
    font-size: 12px;
    color: #00ff00;
    overflow: hidden;
}

.sql-terminal.show {
    display: flex;
}

.sql-terminal-header {
    background: rgba(0, 0, 0, 0.8);
    padding: 10px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.sql-terminal-title {
    font-weight: bold;
    color: #00ff00;
}

.sql-terminal-buttons {
    display: flex;
    gap: 10px;
}

.sql-terminal-clear, .sql-terminal-close {
    background: none;
    border: none;
    color: #ff4444;
    cursor: pointer;
    font-size: 14px;
    padding: 5px 10px;
    border-radius: 3px;
    transition: background 0.3s;
}

.sql-terminal-clear:hover, .sql-terminal-close:hover {
    background: rgba(255, 0, 0, 0.2);
}

.sql-terminal-clear {
    color: #ffa500;
}

.sql-terminal-body {
    flex: 1;
    overflow-y: auto;
    padding: 10px;
    background: rgba(0, 0, 0, 0.7);
}

.sql-query-item {
    margin-bottom: 10px;
    padding: 8px;
    border-radius: 5px;
    border-left: 3px solid;
}

.sql-query-item.SELECT { border-left-color: #007bff; background: rgba(0, 123, 255, 0.1); }
.sql-query-item.INSERT { border-left-color: #28a745; background: rgba(40, 167, 69, 0.1); }
.sql-query-item.UPDATE { border-left-color: #ffc107; background: rgba(255, 193, 7, 0.1); }
.sql-query-item.DELETE { border-left-color: #dc3545; background: rgba(220, 53, 69, 0.1); }
.sql-query-item.OTHER { border-left-color: #6c757d; background: rgba(108, 117, 125, 0.1); }

.sql-query-sql {
    font-family: 'Courier New', monospace;
    white-space: pre-wrap;
    word-break: break-all;
    margin-bottom: 5px;
}

.sql-query-meta {
    font-size: 10px;
    color: #cccccc;
    display: flex;
    justify-content: space-between;
}

.sql-terminal-toggle {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 50px;
    height: 50px;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
    z-index: 9998;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #00ff00;
    font-size: 20px;
    transition: all 0.3s ease;
}

.sql-terminal-toggle:hover {
    background: rgba(0, 0, 0, 0.9);
    transform: scale(1.1);
}

.sql-terminal-toggle.active {
    background: rgba(0, 123, 255, 0.8);
}
</style>

<div id="sql-terminal-toggle" class="sql-terminal-toggle" title="Toggle SQL Terminal">
    <i class="fas fa-terminal"></i>
</div>

<div id="sql-terminal" class="sql-terminal">
    <div class="sql-terminal-header">
        <span class="sql-terminal-title">SQL Query Terminal</span>
        <div class="sql-terminal-buttons">
            <button class="sql-terminal-clear" id="sql-terminal-clear" title="Clear Logs">Clear</button>
            <button class="sql-terminal-close" id="sql-terminal-close">&times;</button>
        </div>
    </div>
    <div class="sql-terminal-body" id="sql-terminal-body">
        <?php if (empty($logs)): ?>
            <div class="sql-query-item OTHER">
                <div class="sql-query-sql">No queries executed yet...</div>
                <div class="sql-query-meta">
                    <span>Waiting for database activity</span>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($logs as $log): ?>
                <div class="sql-query-item <?php echo htmlspecialchars($log['type']); ?>">
                    <div class="sql-query-sql"><?php echo htmlspecialchars($log['sql']); ?></div>
                    <div class="sql-query-meta">
                        <span><?php echo htmlspecialchars($log['timestamp']); ?> | <?php echo htmlspecialchars($log['status']); ?></span>
                        <span><?php echo htmlspecialchars($log['execution_time']); ?>ms</span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const terminal = document.getElementById('sql-terminal');
    const toggle = document.getElementById('sql-terminal-toggle');
    const closeBtn = document.getElementById('sql-terminal-close');
    const clearBtn = document.getElementById('sql-terminal-clear');
    const body = document.getElementById('sql-terminal-body');

    // Toggle terminal visibility
    toggle.addEventListener('click', function() {
        terminal.classList.toggle('show');
        toggle.classList.toggle('active');
    });

    // Close terminal
    closeBtn.addEventListener('click', function() {
        terminal.classList.remove('show');
        toggle.classList.remove('active');
    });

    // Clear logs
    clearBtn.addEventListener('click', function() {
        fetch('/CareerConnectDB/clear_query_logs.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                body.innerHTML = '<div class="sql-query-item OTHER"><div class="sql-query-sql">Logs cleared successfully. Refresh the page to see new queries.</div></div>';
            } else {
                alert('Failed to clear logs: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error clearing logs:', error);
            alert('Error clearing logs');
        });
    });

    // Auto-scroll to bottom when new content is added
    function scrollToBottom() {
        body.scrollTop = body.scrollHeight;
    }

    // Initial scroll
    scrollToBottom();

    // Optional: Add keyboard shortcut (Ctrl+Shift+Q to toggle)
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.shiftKey && e.key === 'Q') {
            e.preventDefault();
            terminal.classList.toggle('show');
            toggle.classList.toggle('active');
        }
    });
});
</script>
<?php
}
?>