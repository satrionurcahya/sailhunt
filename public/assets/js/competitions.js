/**
 * =========================================================
 * DAFTAR LOMBA
 * Page-specific JavaScript
 * ========================================================= */

document.addEventListener('DOMContentLoaded', function () {
    'use strict';


    /* =====================================================
       RE-INDEX TEAMS
       ===================================================== */

    function reindexTeams(container) {
        const rows = container.querySelectorAll('.team-row');

        rows.forEach((row, newIndex) => {

            const label = row.querySelector('.team-label');

            if (label) {
                label.textContent = 'Tim ' + (newIndex + 1);
            }


            const inputs = row.querySelectorAll(
                '.participant-name-input'
            );


            inputs.forEach((input, participantIndex) => {

                const competitionId =
                    input.dataset.competition;


                input.dataset.team = newIndex;

                input.dataset.participant =
                    participantIndex;


                input.name =
                    `competitions[${competitionId}][teams][${newIndex}][${participantIndex}]`;

            });

        });
    }


    /* =====================================================
       EXPAND / COLLAPSE PARTICIPANT AREA
       ===================================================== */

    document.querySelectorAll('.lomba-checkbox').forEach(cb => {

        cb.addEventListener('change', function () {

            const targetId = this.dataset.target;

            const body =
                document.getElementById(targetId);

            const card =
                this.closest('.comp-card-single');


            if (!body) {
                return;
            }


            body.style.display =
                this.checked ? 'block' : 'none';


            if (card) {

                card.classList.toggle(
                    'is-active',
                    this.checked
                );

            }

        });

    });


    /* =====================================================
       ADD TEAM
       ===================================================== */

    document.querySelectorAll('.add-team-btn').forEach(btn => {

        btn.addEventListener('click', function () {

            const competitionId =
                this.dataset.competition;


            const container =
                document.getElementById(
                    'teams-' + competitionId
                );


            if (!container) {
                return;
            }


            const wrapper =
                container.closest('.teams-wrapper');


            if (!wrapper) {
                return;
            }


            const maxTeams =
                parseInt(
                    wrapper.dataset.maxTeams,
                    10
                );


            const teamSize =
                parseInt(
                    wrapper.dataset.teamSize,
                    10
                );


            const currentTeams =
                container.querySelectorAll(
                    '.team-row'
                ).length;


            const deadlinePassed =
                wrapper.dataset.deadlinePassed === '1';


            if (deadlinePassed) {
                return;
            }


            if (currentTeams >= maxTeams) {

                alert('Maksimal tim tercapai.');

                return;
            }


            /* ---------------------------------------------
               CREATE TEAM ROW
               --------------------------------------------- */

            const div =
                document.createElement('div');

            div.className = 'team-row';


            /* TEAM LABEL */

            const label =
                document.createElement('span');

            label.className = 'team-label';

            label.textContent =
                'Tim ' + (currentTeams + 1);


            div.appendChild(label);


            /* TEAM INPUTS */

            const inputsWrap =
                document.createElement('div');

            inputsWrap.className =
                'team-inputs';


            for (let i = 0; i < teamSize; i++) {

                const inputWrap =
                    document.createElement('div');

                inputWrap.className =
                    'team-input-wrap';


                const input =
                    document.createElement('input');

                input.type = 'text';


                input.name =
                    `competitions[${competitionId}][teams][${currentTeams}][${i}]`;


                input.className =
                    'form-control form-control-sm participant-name-input';


                input.dataset.competition =
                    competitionId;


                input.dataset.team =
                    currentTeams;


                input.dataset.participant =
                    i;


                input.placeholder =
                    'Nama Peserta ' + (i + 1);


                input.autocomplete =
                    'off';


                input.disabled =
                    deadlinePassed;


                inputWrap.appendChild(input);

                inputsWrap.appendChild(inputWrap);

            }


            div.appendChild(inputsWrap);


            /* REMOVE BUTTON */

            const delBtn =
                document.createElement('button');

            delBtn.type = 'button';

            delBtn.className =
                'btn btn-sm btn-outline-danger remove-team-btn';

            delBtn.title =
                'Hapus tim';


            delBtn.innerHTML =
                '<i class="fas fa-trash-alt mr-1"></i> Hapus Tim';


            div.appendChild(delBtn);


            container.appendChild(div);


            /* FOCUS FIRST INPUT */

            const firstInput =
                div.querySelector('input');


            if (firstInput) {
                firstInput.focus();
            }

        });

    });


    /* =====================================================
       VALIDATION BEFORE SUBMIT
       ===================================================== */

    const registrationForm =
        document.querySelector(
            'form[action*="storeBatch"]'
        );


    if (registrationForm) {

        registrationForm.addEventListener(
            'submit',
            function (e) {

                let hasError = false;


                document
                    .querySelectorAll(
                        '.lomba-checkbox:checked'
                    )
                    .forEach(cb => {

                        const body =
                            document.getElementById(
                                cb.dataset.target
                            );


                        if (
                            !body ||
                            body.style.display === 'none'
                        ) {
                            return;
                        }


                        body
                            .querySelectorAll(
                                '.participant-name-input'
                            )
                            .forEach(input => {

                                if (!input.value.trim()) {

                                    input.classList.add(
                                        'is-invalid'
                                    );

                                    hasError = true;

                                } else {

                                    input.classList.remove(
                                        'is-invalid'
                                    );

                                }

                            });

                    });


                if (hasError) {

                    e.preventDefault();


                    const firstInvalid =
                        document.querySelector(
                            '.participant-name-input.is-invalid'
                        );


                    if (firstInvalid) {

                        firstInvalid.focus();


                        firstInvalid.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });

                    }


                    alert(
                        'Mohon isi semua nama peserta pada lomba yang dipilih.'
                    );

                }

            }
        );

    }


    /* =====================================================
       REMOVE TEAM
       Event delegation digunakan agar tim yang baru
       ditambahkan juga dapat dihapus.
       ===================================================== */

    document
        .querySelectorAll('.teams-container')
        .forEach(container => {

            container.addEventListener(
                'click',
                function (e) {

                    const removeBtn =
                        e.target.closest(
                            '.remove-team-btn'
                        );


                    if (!removeBtn) {
                        return;
                    }


                    const row =
                        removeBtn.closest(
                            '.team-row'
                        );


                    if (!row) {
                        return;
                    }


                    const teamsContainer =
                        row.parentNode;


                    const teamRows =
                        teamsContainer.querySelectorAll(
                            '.team-row'
                        );


                    if (teamRows.length <= 1) {

                        alert(
                            'Minimal harus ada 1 tim.'
                        );

                        return;
                    }


                    row.remove();


                    reindexTeams(
                        teamsContainer
                    );

                }
            );

        });

});