document.getElementById('sectionChooser').addEventListener('change', function() {
                                                                var selection = this.value;
                                                                document.querySelectorAll('.panel').forEach(function(panel) {
                                                                    panel.style.display = 'none';
                                                                });
                                                                if (selection) {
                                                                    var targetPanel = document.getElementById(selection);
                                                                    if (targetPanel) {
                                                                        targetPanel.style.display = 'block';
                                                                    }
                                                                }
                                                            });