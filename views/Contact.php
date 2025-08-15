<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Liên hệ - Ease Fashion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .contact-section {
            padding: 60px 0;
            background-color: #f8f9fa;
        }

        .contact-section h2 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .contact-section p {
            color: #6c757d;
        }

        .card input,
        .card textarea {
            border-radius: 10px;
        }

        .card {
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: linear-gradient(to right, #007bff, #0056b3);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(to right, #0056b3, #003d99);
        }
    </style>

</head>

<body>
    <?php include('./views/components/header.php'); ?>

    <section class="contact-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Liên Hệ Với Chúng Tôi</h2>
                <p>Hãy để lại lời nhắn nếu bạn có bất kỳ câu hỏi hoặc thắc mắc nào!</p>
            </div>

            <div class="row justify-content-center">
                <!-- Form liên hệ -->
                <div class="col-md-10 col-lg-6">
                    <div class="card shadow-sm p-4 border-0 rounded-4">
                        <form action="#" method="post">
                            <div class="mb-3">
                                <label for="name" class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" id="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Địa chỉ Email</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Chủ đề</label>
                                <input type="text" class="form-control" id="subject" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Nội dung</label>
                                <textarea class="form-control" id="message" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 rounded-pill">Gửi liên hệ</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>




    <!-- Thông tin liên hệ -->

    <?php include('./views/components/footer.php'); ?>