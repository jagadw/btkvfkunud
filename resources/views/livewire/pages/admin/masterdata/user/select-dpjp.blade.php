<div class="">
    <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
        <span>DPJP</span>
        <span class="ms-1" data-bs-toggle="tooltip" title="Specify a target name for future usage and reference">
            <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
            </i>
        </span>
    </label>
    <div class="custom-select2" id="customSelect2Mahasiswa">
        <div class="custom-select2-selected form-control form-control-solid" tabindex="0">
            {{ $selectedMahasiswa ? ($mahasiswas->firstWhere('id', $selectedMahasiswa)->nama ?? 'Select DPJP') : 'Select DPJP' }}
        </div>
        <div class="custom-select2-dropdown" style="display: none;">
            <input type="text" class="form-control mb-2 custom-select2-search" placeholder="Search DPJP...">
            <ul class="list-group custom-select2-list" style="max-height: 200px; overflow-y: auto;">
                @foreach ($dpjps as $mahasiswa)
                <li class="list-group-item custom-select2-option{{ $selectedMahasiswa == $mahasiswa->id ? ' active' : '' }}" data-value="{{ $mahasiswa->id }}">
                    {{ $mahasiswa->nama }}
                </li>
                @endforeach
            </ul>
        </div>
        <input type="hidden" wire:model="selectedMahasiswa" id="customSelect2MahasiswaInput">
    </div>

    <style>
        .custom-select2 {
            position: relative;
            width: 100%;
        }

        .custom-select2-selected {
            cursor: pointer;
            background: #f5f8fa;
        }

        .custom-select2-dropdown {
            position: absolute;
            width: 100%;
            background: #fff;
            border: 1px solid #e4e6ef;
            z-index: 1000;
            border-radius: .475rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .05);
        }

        .custom-select2-list {
            margin: 0;
            padding: 0;
        }

        .custom-select2-option {
            cursor: pointer;
        }

        .custom-select2-option:hover,
        .custom-select2-option.active {
            background: #f1faff;
        }
    </style>

    <script>
        const select2 = document.getElementById('customSelect2Mahasiswa');
        const selected = select2.querySelector('.custom-select2-selected');
        const dropdown = select2.querySelector('.custom-select2-dropdown');
        const search = select2.querySelector('.custom-select2-search');
        const options = select2.querySelectorAll('.custom-select2-option');
        const hiddenInput = document.getElementById('customSelect2MahasiswaInput');

        // Set selected option on load
        function setActiveOption() {
            options.forEach(function(option) {
                if (option.getAttribute('data-value') === hiddenInput.value) {
                    option.classList.add('active');
                    selected.textContent = option.textContent;
                } else {
                    option.classList.remove('active');
                }
            });
        }
        setActiveOption();

        // Toggle dropdown
        selected.addEventListener('click', function() {
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
            search.value = '';
            filterOptions('');
            search.focus();
        });

        // Hide dropdown on outside click
        document.addEventListener('click', function(e) {
            if (!select2.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });

        // Option click
        options.forEach(function(option) {
            option.addEventListener('click', function() {
                options.forEach(opt => opt.classList.remove('active'));
                option.classList.add('active');
                selected.textContent = option.textContent;
                hiddenInput.value = option.getAttribute('data-value');
                dropdown.style.display = 'none';
                // Livewire update
                hiddenInput.dispatchEvent(new Event('input', {
                    bubbles: true
                }));
            });
        });

        // Listen for Livewire updates to hidden input
        hiddenInput.addEventListener('input', setActiveOption);

        // Search filter
        search.addEventListener('input', function() {
            filterOptions(this.value);
        });

        function filterOptions(query) {
            const lower = query.toLowerCase();
            options.forEach(function(option) {
                if (option.textContent.toLowerCase().includes(lower)) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            });
        }
    </script>
</div>