import sys
import face_recognition

def compare_faces(image1_path, image2_path):
    try:
        # Load images
        image1 = face_recognition.load_image_file(image1_path)
        image2 = face_recognition.load_image_file(image2_path)

        # Encode faces
        encodings1 = face_recognition.face_encodings(image1)
        encodings2 = face_recognition.face_encodings(image2)

        if not encodings1:
            print("Error: No face found in image 1")
            return
        if not encodings2:
            print("Error: No face found in image 2")
            return

        encoding1 = encodings1[0]
        encoding2 = encodings2[0]

        # Compare faces
        result = face_recognition.compare_faces([encoding1], encoding2)

        if result[0]:
            print("Match ✅")
        else:
            print("No Match ❌")

    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    compare_faces(sys.argv[1], sys.argv[2])
