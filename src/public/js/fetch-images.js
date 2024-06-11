document.querySelector('form').addEventListener('submit', function (e) {
    e.preventDefault();

    document.querySelector('.loader').style.display = 'flex';

    document.querySelector('input[name="url"]').disabled = true;
    document.querySelector('button').disabled = true;

    const url = document.querySelector('input[name="url"]').value;

    fetch(this.action + '?url=' + url)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('images-container');
            container.innerHTML = '';

            const table = document.createElement('table');
            container.appendChild(table);

            const tbody = document.createElement('tbody');
            table.appendChild(tbody);

            let tr = document.createElement('tr');
            tbody.appendChild(tr);

            data.images.forEach((image, index) => {
                if (index % 4 === 0 && index !== 0) {
                    tr = document.createElement('tr');
                    tbody.appendChild(tr);
                }

                const td = document.createElement('td');
                tr.appendChild(td);

                const a = document.createElement('a');
                a.href = image;
                a.target = '_blank';
                td.appendChild(a);

                const img = document.createElement('img');
                img.src = image;
                img.style.width = '100px';
                img.style.height = '100px';
                a.appendChild(img);
            });

            const totalSize = data.totalSize && data.totalSize > 0
                ? data.totalSize / 1024 / 1024
                : 0;

            const p = document.createElement('p');
            p.innerHTML = [
                'Total images: ' + data.images.length,
                'Total size: ' + totalSize.toFixed(2) + ' MB',
            ].join('<br>');
            container.appendChild(p);

            document.querySelector('input[name="url"]').disabled = false;
            document.querySelector('button').disabled = false;

            document.querySelector('.loader').style.display = 'none';
        });
});
