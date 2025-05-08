<?php include 'search.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Results Search</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .search-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #3498db;
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }
        mark {
            background-color: #ffeb3b;
            padding: 0 2px;
            border-radius: 3px;
        }
        .suggestions {
            position: absolute;
            z-index: 1000;
            width: calc(100% - 120px);
            max-height: 200px;
            overflow-y: auto;
            background: white;
            border: 1px solid #ddd;
            border-radius: 0 0 4px 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .suggestion-item {
            padding: 8px 15px;
            cursor: pointer;
        }
        .suggestion-item:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="search-container">
            <h2 class="text-center mb-4">
                <i class="fas fa-search"></i> Student Results Search
            </h2>
            
            <form method="post" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" id="searchInput" class="form-control form-control-lg" 
                           placeholder="Enter student name or roll number" 
                           value="<?= htmlspecialchars($search ?? '') ?>"
                           autocomplete="off">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </div>
            </form>
            
            <?php if (!empty($suggestions) && empty($results) && !empty($search)): ?>
                <div class="suggestions">
                    <?php foreach ($suggestions as $suggestion): ?>
                        <div class="suggestion-item" onclick="selectSuggestion('<?= htmlspecialchars($suggestion) ?>')">
                            <?= htmlspecialchars($suggestion) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($results)): ?>
                <div class="results-container">
                    <h4 class="mb-3">Search Results</h4>
                    <div class="row">
                        <?php foreach ($results as $row): ?>
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <?= $row['highlighted_name'] ?? htmlspecialchars($row['student_name']) ?>
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text"><strong><i class="fas fa-id-card"></i> Roll:</strong> 
                                            <?= isset($search_term) ? 
                                                preg_replace("/(".$search_term.")/i", "<mark>$1</mark>", htmlspecialchars($row['roll_number'])) : 
                                                htmlspecialchars($row['roll_number']) ?>
                                        </p>
                                        <p class="card-text"><strong><i class="fas fa-book"></i> Subject:</strong> <?= htmlspecialchars($row['subject']) ?></p>
                                        <p class="card-text"><strong><i class="fas fa-percentage"></i> Marks:</strong> <?= htmlspecialchars($row['marks']) ?></p>
                                        <p class="card-text"><strong><i class="fas fa-award"></i> Grade:</strong> <?= htmlspecialchars($row['grade']) ?></p>
                                        <p class="card-text"><strong><i class="fas fa-calendar-alt"></i> Date:</strong> <?= htmlspecialchars($row['exam_date']) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])): ?>
                <div class="alert alert-warning text-center py-4">
                    <h4><i class="fas fa-exclamation-triangle"></i> No records found</h4>
                    <p class="mb-0">Try searching with different terms</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script>
        function selectSuggestion(suggestion) {
            document.getElementById('searchInput').value = suggestion;
            document.forms[0].submit();
        }
        
        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.suggestions') && !e.target.closest('#searchInput')) {
                document.querySelector('.suggestions')?.style.display = 'none';
            }
        });
    </script>
</body>
</html>