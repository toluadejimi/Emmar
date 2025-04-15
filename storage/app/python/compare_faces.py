import sys
import face_recognition
import traceback

def compare_faces(image1_path, image2_path):
    try:
        print(f"Image1 path: {image1_path}", flush=True)
        print(f"Image2 path: {image2_path}", flush=True)

        # Load images
        image1 = face_recognition.load_image_file(image1_path)
        image2 = face_recognition.load_image_file(image2_path)

        # Encode faces
        encodings1 = face_recognition.face_encodings(image1)
        encodings2 = face_recognition.face_encodings(image2)

        print(f"Encodings1 found: {len(encodings1)}", flush=True)
        print(f"Encodings2 found: {len(encodings2)}", flush=True)

        if not encodings1:
            print("Error: No face found in image 1", flush=True)
            return
        if not encodings2:
            print("Error: No face found in image 2", flush=True)
            return

        encoding1 = encodings1[0]
        encoding2 = encodings2[0]

        # Compare faces
        result = face_recognition.compare_faces([encoding1], encoding2)

        if result[0]:
            print("Match ✅", flush=True)
        else:
            print("No Match ❌", flush=True)

    except Exception:
        print("Error occurred during comparison:", flush=True)
        traceback.print_exc(file=sys.stdout)

if __name__ == "__main__":
    compare_faces(sys.argv[1], sys.argv[2])
